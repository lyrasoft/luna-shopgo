<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Service;

use Lyrasoft\Luna\Entity\User;
use Lyrasoft\Sequence\Service\SequenceService;
use Lyrasoft\ShopGo\Cart\CartData;
use Lyrasoft\ShopGo\Cart\CartItem;
use Lyrasoft\ShopGo\Cart\Price\PriceObject;
use Lyrasoft\ShopGo\Cart\Price\PriceSet;
use Lyrasoft\ShopGo\Data\PaymentData;
use Lyrasoft\ShopGo\Data\ShippingData;
use Lyrasoft\ShopGo\Entity\Address;
use Lyrasoft\ShopGo\Entity\Location;
use Lyrasoft\ShopGo\Entity\Order;
use Lyrasoft\ShopGo\Entity\OrderHistory;
use Lyrasoft\ShopGo\Entity\OrderItem;
use Lyrasoft\ShopGo\Entity\OrderState;
use Lyrasoft\ShopGo\Entity\OrderTotal;
use Lyrasoft\ShopGo\Entity\Product;
use Lyrasoft\ShopGo\Entity\ProductVariant;
use Lyrasoft\ShopGo\Enum\OrderHistoryType;
use Lyrasoft\ShopGo\Event\AfterOrderCreateEvent;
use Lyrasoft\ShopGo\Event\AfterOrderDetailCreatedEvent;
use Lyrasoft\ShopGo\Event\BeforeOrderCreateEvent;
use Lyrasoft\ShopGo\Payment\PaymentService;
use Lyrasoft\ShopGo\Shipping\ShippingService;
use Lyrasoft\ShopGo\ShopGoPackage;
use Windwalker\Core\Form\Exception\ValidateFailException;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Core\Router\RouteUri;
use Windwalker\Data\Collection;
use Windwalker\ORM\ORM;
use Windwalker\Utilities\Cache\InstanceCacheTrait;

use function Windwalker\collect;

/**
 * The CheckoutService class.
 */
class CheckoutService
{
    use InstanceCacheTrait;
    use TranslatorTrait;

    public function __construct(
        protected ORM $orm,
        protected ShopGoPackage $shopGo,
        protected OrderService $orderService,
        protected OrderHistoryService $orderHistoryService,
        protected LocationService $locationService,
        protected AddressService $addressService,
        protected PaymentService $paymentService,
        protected ShippingService $shippingService,
        protected ?SequenceService $sequenceService = null,
    ) {
    }

    public function prepareAddressData(
        ?int $addressId,
        array $data,
        PaymentData|ShippingData $addressData,
        User $user
    ): Location {
        if ($addressId) {
            $address = $this->orm->mustFindOne(Address::class, $addressId);

            if (!$user->isLogin()) {
                throw new ValidateFailException('Only login user can select address');
            }

            if ($user->getId() !== $address->getUserId()) {
                throw new ValidateFailException('This address is not belongs to user');
            }

            $location = $this->orm->mustFindOne(Location::class, $address->getLocationId());
            [$country, $state, $city] = $this->locationService->getPathFromLocation($location);

            $addressData
                ->fillFrom($address)
                ->setCountry($country?->getTitle() ?? '')
                ->setState($state?->getTitle() ?? '')
                ->setCity($city?->getTitle() ?? '')
                ->setFormatted(
                    AddressService::formatByLocation($address, $country, true)
                );
        } else {
            $location = $this->orm->mustFindOne(Location::class, $data['location_id']);
            [$country, $state, $city] = $this->locationService->getPathFromLocation($location);

            $addressData
                ->setLocationId($location->getId())
                ->setFirstname($data['firstname'])
                ->setLastname($data['lastname'])
                ->setEmail($data['email'])
                ->setPhone($data['phone'])
                ->setMobile($data['mobile'])
                ->setCompany($data['company'])
                ->setVat($data['vat'])
                ->setAddress1($data['address1'])
                ->setAddress2($data['address2'])
                ->setPostcode($data['postcode'])
                ->setCountry($country?->getTitle() ?? '')
                ->setState($state?->getTitle() ?? '')
                ->setCity($city?->getTitle() ?? '')
                ->setName(trim($addressData->getFirstname() . ' ' . $addressData->getLastname()))
                ->setFormatted(
                    AddressService::formatByLocation($addressData, $country, true)
                );

            if ($data['save'] ?? false) {
                $address = new Address();
                $address->fillFrom($addressData);

                $this->orm->createOne(Address::class, $address);

                $addressData->setAddressId($address->getId());
            }
        }

        return $location;
    }

    /**
     * @param  Order     $order
     * @param  CartData  $cartData
     *
     * @return  Order
     *
     * @throws \ReflectionException
     */
    public function createOrder(Order $order, CartData $cartData, array $checkoutData = []): Order
    {
        $totals = $cartData->getTotals();

        $order->setTotal($cartData->getTotals()['grand_total']->getPrice()->toFloat());

        $paymentInstance = $this->paymentService->getInstanceById($order->getPaymentId());
        $shippingInstance = $this->shippingService->getInstanceById($order->getShippingId());

        $state = $this->orm->findOne(OrderState::class, $paymentInstance->getData()->getOrderStateId());

        if (!$state) {
            $state = $this->orm->mustFindOne(OrderState::class, ['default' => 1]);
        }

        $order->setState($state);
        $order->setExpiryOn(
            $this->shopGo->config('checkout.default_expiry') ?? '+7days'
        );

        $paymentData = $order->getPaymentData();
        $shippingData = $order->getShippingData();

        $paymentData->setPaymentTitle($paymentInstance->getData()->getTitle());
        $shippingData->setShippingTitle($shippingInstance->getData()->getTitle());

        $order = $paymentInstance->prepareOrder($order, $cartData, $checkoutData);
        $order = $shippingInstance->prepareOrder($order, $cartData, $checkoutData);

        $event = $this->shopGo->emit(
            BeforeOrderCreateEvent::class,
            compact(
                'order',
                'cartData',
                'totals'
            )
        );

        $order = $event->getOrder();
        $cartData = $event->getCartData();
        $totals = $event->getTotals();

        // Create Order
        /** @var Order $order */
        $order = $this->orm->createOne(Order::class, $order);

        $this->prepareOrderAndPaymentNo($order, $paymentInstance->isTest());

        $event = $this->shopGo->emit(
            AfterOrderCreateEvent::class,
            compact(
                'order',
                'cartData',
                'totals'
            )
        );

        $order = $event->getOrder();
        $cartData = $event->getCartData();
        $totals = $event->getTotals();

        $orderItems = $this->createOrderItemsAndAttachments($order, $cartData);
        $orderTotals = $this->createOrderTotals($order, $totals);
        $orderHistory = $this->createNewHistory($order);

        $event = $this->shopGo->emit(
            AfterOrderDetailCreatedEvent::class,
            compact(
                'order',
                'cartData',
                'totals',
                'orderItems',
                'orderTotals',
                'orderHistory',
            )
        );

        // Create note history
        if ($order->getNote() !== '') {
            $this->orderHistoryService->createHistory(
                $order,
                null,
                OrderHistoryType::MEMBER(),
                $order->getNote()
            );
        }

        return $event->getOrder();
    }

    public function createOrderAndNotify(Order $order, CartData $cartData): Order
    {
        $order = $this->createOrder($order, $cartData);

        // Todo: Notify user
        // $this->mailer->createMessage('訂單已成立，感謝您的訂購！')
        //     ->to($order->getEmail())
        //     ->renderBody(
        //         'mail.order.new-order',
        //         compact('order', 'subOrders', 'user', 'cartData')
        //     )
        //     ->send();
        //
        // Todo: Notify admin
        // // Notify vendor picking
        // if ($order->getShipping()->isCod()) {
        //     foreach ($subOrders as $subOrder) {
        //         $this->orderService->notifyVendor($subOrder, $subOrder->getState());
        //     }
        // }

        return $order;
    }

    /**
     * @param  Order            $order
     * @param  OrderState|null  $state
     *
     * @return OrderHistory
     */
    protected function createNewHistory(Order $order, ?OrderState $state = null): OrderHistory
    {
        $msg = $this->trans('shopgo.order.history.new.message');

        if (trim($order->getNote())) {
            $msg .= $this->trans('shopgo.order.history.new.note', note: $order->getNote());
        }

        $state ??= $this->orm->mustFindOne(OrderState::class, $order->getStateId());

        return $this->orderHistoryService->createHistory(
            $order,
            $state,
            OrderHistoryType::SYSTEM(),
            $msg,
            true
        );
    }

    /**
     * @param  Order     $order
     * @param  CartData  $cartData
     *
     * @return  Collection<OrderItem>
     *
     * @throws \ReflectionException
     */
    public function createOrderItemsAndAttachments(Order $order, CartData $cartData): Collection
    {
        $items = $cartData->getCheckedItems();

        $orderItems = collect();

        foreach ($items as $item) {
            $orderItem = $this->cartItemToOrderItem($item);
            $orderItem->setOrderId($order->getId());

            $orderItem = $this->orm->createOne(OrderItem::class, $orderItem);

            $order->getOrderItems()->attach($orderItem);

            $orderItems[] = $orderItem;

            foreach ($item->getAttachments() as $attachment) {
                $attachItem = $this->cartItemToOrderItem($attachment);

                $attachItem->setOrderId($order->getId());
                $attachItem->setParentId($orderItem->getId());
                $attachItem->setAttachmentId((int) $attachment->getKey());

                $attachItem = $this->orm->createOne(OrderItem::class, $attachItem);

                $orderItem->getAttachments()->attach($attachItem);
            }
        }

        return $orderItems;
    }

    public function getProduct(int $productId): Product
    {
        return $this->cacheStorage['product.' . $productId]
            ??= $this->orm->mustFindOne(Product::class, $productId);
    }

    /**
     * @param  Order     $order
     * @param  PriceSet  $totals
     *
     * @return Collection<OrderTotal>
     */
    protected function createOrderTotals(Order $order, PriceSet $totals): Collection
    {
        $i = 1;

        $orderTotals = collect();

        /** @var PriceObject $total */
        foreach ($totals as $total) {
            $orderTotal = new OrderTotal();
            $orderTotal->setOrderId($order->getId());
            $orderTotal->setTitle($total->getLabel());
            $orderTotal->setType(
                str_starts_with($total->getName(), 'discount')
                    ? 'discount'
                    : 'total'
            );
            $orderTotal->setCode($total->getName());
            $orderTotal->setTitle($total->getLabel());
            $orderTotal->setValue($total->getPrice()->toFloat());
            $orderTotal->setParams($total->getParams());
            $orderTotal->setDiscountId($total->getParams()['discount_id'] ?? 0);
            $orderTotal->setDiscountType($total->getParams()['discount_type'] ?? '');
            $orderTotal->setOrdering($i);
            $orderTotal->setProtect($orderTotal->getType() === 'total');

            $this->orm->createOne(OrderTotal::class, $orderTotal);

            $orderTotals[] = $orderTotal;

            $i++;
        }

        return $orderTotals;
    }

    /**
     * @param  Order  $order
     * @param  bool   $test
     *
     * @return Order
     */
    protected function prepareOrderAndPaymentNo(Order $order, bool $test = false): Order
    {
        $no = $this->orderService->createOrderNo($order->getId());
        $tradeNo = $this->orderService->getPaymentNo($no, $test);

        // Save NO
        $this->orm->updateWhere(
            Order::class,
            ['no' => $no, 'payment_no' => $tradeNo],
            ['id' => $order->getId()]
        );

        $order->setNo($no);
        $order->setPaymentNo($tradeNo);

        return $order;
    }

    /**
     * @param  CartItem       $item
     *
     * @return  OrderItem
     *
     * @throws \ReflectionException
     */
    protected function cartItemToOrderItem(CartItem $item): OrderItem
    {
        /** @var ProductVariant $variant */
        $variant = $item->getVariant()->getData();
        $product = $this->orm->toEntity(
            Product::class,
            $variant->product ?? $this->getProduct($variant->getProductId())
        );

        $orderItem = new OrderItem();
        $orderItem->setProductId($product->getId());
        $orderItem->setVariantId($variant->getId());
        $orderItem->setVariantHash($variant->getHash());
        $orderItem->setKey($item->getKey());
        $orderItem->setTitle($product->getTitle());
        $orderItem->setVariantTitle($variant->getTitle());
        $orderItem->setBasePriceUnit($variant->getPrice());
        $orderItem->setPriceUnit($item->getPriceSet()['final']->toFloat());
        $orderItem->setQuantity($item->getQuantity());
        $orderItem->setImage($variant->getCover() ?: $product->getCover());
        $orderItem->setTotal($item->getPriceSet()['final_total']->toFloat());
        $orderItem->setPriceSet(clone $item->getPriceSet());
        $orderItem->setProductData(
            [
                'product' => $product->toCollection()
                    ->except(['searchIndex']),
                'variant' => $variant->toCollection()
                    ->except(['searchIndex']),
            ]
        );
        $orderItem->setOptions($variant->getOptions());

        $data = $this->orm->extractEntity($orderItem);

        return $orderItem;
    }

    public function processPayment(Order $order, RouteUri $completeUrl)
    {
        $paymentInstance = $this->paymentService->getInstanceById($order->getPaymentId());

        return $paymentInstance->processCheckout($order, $completeUrl);
    }
}

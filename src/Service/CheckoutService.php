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
use Windwalker\Core\Form\Exception\ValidateFailException;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\ORM\ORM;
use Windwalker\Utilities\Cache\InstanceCacheTrait;

/**
 * The CheckoutService class.
 */
class CheckoutService
{
    use InstanceCacheTrait;
    use TranslatorTrait;

    public function __construct(
        protected ORM $orm,
        protected OrderService $orderService,
        protected OrderHistoryService $orderHistoryService,
        protected LocationService $locationService,
        protected AddressService $addressService,
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
    public function createOrder(Order $order, CartData $cartData): Order
    {
        $totals = $cartData->getTotals();

        $order->setTotal($cartData->getTotals()['grand_total']->getPrice()->toFloat());

        // Todo: Get state from shipping/payment
        $state = $this->orm->mustFindOne(OrderState::class, ['default' => 1]);
        $order->setState($state);

        $this->prepareOrderItems($order, $cartData);
        $this->prepareOrderTotals($order, $totals);

        // Create Order
        /** @var Order $order */
        $order = $this->orm->createOne(Order::class, $order);

        $this->prepareOrderAndPaymentNo($order);

        $this->createNewHistory($order);

        return $order;
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
     * @param  Order  $order
     *
     * @return OrderHistory
     */
    protected function createNewHistory(Order $order): OrderHistory
    {
        $msg = $this->trans('shopgo.order.history.new.message');

        if (trim($order->getNote())) {
            $msg .= $this->trans('shopgo.order.history.new.note', note: $order->getNote());
        }

        return $this->orderHistoryService->createHistory(
            $order,
            null,
            OrderHistoryType::SYSTEM(),
            $msg,
            true
        );
    }

    /**
     * @param  Order     $order
     * @param  CartData  $cartData
     *
     * @return  array<OrderItem>
     *
     * @throws \ReflectionException
     */
    public function prepareOrderItems(Order $order, CartData $cartData): array
    {
        $items = $cartData->getItems();

        $orderItems = [];

        foreach ($items as $item) {
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
            $orderItem->setTitle($product->getTitle());
            $orderItem->setVariantTitle($variant->getTitle());
            $orderItem->setBasePriceUnit($variant->getPrice());
            $orderItem->setPriceUnit($item->getPriceSet()['final']->toFloat());
            $orderItem->setQuantity($item->getQuantity());
            $orderItem->setImage($variant->getCover() ?: $product->getCover());
            $orderItem->setTotal($item->getPriceSet()['final_total']->toFloat());
            $orderItem->setPriceSet($item->getPriceSet());
            $orderItem->setOptions($variant->getOptions());

            $order->getOrderItems()->attach($orderItem);
            $orderItems[] = $orderItem;
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
     * @return array<OrderTotal>
     */
    protected function prepareOrderTotals(Order $order, PriceSet $totals): array
    {
        $i = 1;

        $orderTotals = [];

        /** @var PriceObject $total */
        foreach ($totals as $total) {
            $orderTotal = new OrderTotal();
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

            $orderTotals[] = $orderTotal;
            $order->getTotals()->attach($orderTotal);

            $i++;
        }

        return $orderTotals;
    }

    /**
     * @param  Order  $order
     *
     * @return Order
     */
    protected function prepareOrderAndPaymentNo(Order $order): Order
    {
        $no = $this->orderService->createOrderNo($order->getId());
        $tradeNo = $this->orderService->getPaymentNo($no, true);

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
}

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
use Windwalker\Core\Application\ApplicationInterface;
use Windwalker\Core\Form\Exception\ValidateFailException;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Core\Mailer\MailerInterface;
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
        protected ApplicationInterface $app,
        protected ORM $orm,
        protected ShopGoPackage $shopGo,
        protected CurrencyService $currencyService,
        protected OrderService $orderService,
        protected OrderHistoryService $orderHistoryService,
        protected LocationService $locationService,
        protected AddressService $addressService,
        protected PaymentService $paymentService,
        protected ShippingService $shippingService,
        protected MailerInterface $mailer,
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

            if ($user->id !== $address->userId) {
                throw new ValidateFailException('This address is not belongs to user');
            }

            $location = $this->orm->mustFindOne(Location::class, $address->locationId);
            [$country, $state, $city] = $this->locationService->getPathFromLocation($location);

            $addressData->fillFrom($address);
            $addressData->country = $country?->title ?? '';
            $addressData->state = $state?->title ?? '';
            $addressData->city = $city?->title ?? '';
            $addressData->formatted = AddressService::formatByLocation($address, $country, true);
        } else {
            $location = $this->orm->mustFindOne(Location::class, $data['location_id']);
            [$country, $state, $city] = $this->locationService->getPathFromLocation($location);

            $addressData->locationId = $location->id;
            $addressData->firstname = $data['firstname'];
            $addressData->lastname = $data['lastname'];
            $addressData->email = $data['email'];
            $addressData->phone = $data['phone'];
            $addressData->mobile = $data['mobile'];
            $addressData->company = $data['company'];
            $addressData->vat = $data['vat'];
            $addressData->address1 = $data['address1'];
            $addressData->address2 = $data['address2'];
            $addressData->postcode = $data['postcode'];
            $addressData->country = $country?->title ?? '';
            $addressData->state = $state?->title ?? '';
            $addressData->city = $city?->title ?? '';
            $addressData->name = trim($data['firstname'] . ' ' . $data['lastname']);
            $addressData->formatted = AddressService::formatByLocation($addressData, $country, true);

            if ($data['save'] ?? false) {
                $address = new Address();
                $address->fillFrom($addressData);

                $this->orm->createOne(Address::class, $address);

                $addressData->addressId = $address->id;
            }
        }

        return $location;
    }

    /**
     * @param  Order     $order
     * @param  CartData  $cartData
     * @param  array     $checkoutData
     *
     * @return  Order
     *
     * @throws \ReflectionException
     */
    public function createOrder(Order $order, CartData $cartData, array $checkoutData = []): Order
    {
        $totals = $cartData->getTotals();
        $grandTotal = $cartData->getTotals()['grand_total']->getPrice()->toFloat();

        if ($grandTotal < 0) {
            throw new ValidateFailException('Cannot process checkout for negative price.');
        }

        $order->total = $grandTotal;

        $paymentInstance = $this->paymentService->getInstanceById($order->paymentId);
        $shippingInstance = $this->shippingService->getInstanceById($order->shippingId);

        $state = $this->orm->findOne(OrderState::class, $paymentInstance->getData()->orderStateId);

        if (!$state) {
            $state = $this->orm->mustFindOne(OrderState::class, ['default' => 1]);
        }

        $order->state = $state;
        $order->expiryOn = $this->shopGo->config('checkout.default_expiry') ?? '+7days';

        $paymentData = $order->paymentData;
        $shippingData = $order->shippingData;

        $paymentData->paymentTitle = $paymentInstance->getData()->title;
        $shippingData->shippingTitle = $shippingInstance->getData()->title;

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
        if ($order->note !== '') {
            $this->orderHistoryService->createHistory(
                $order,
                null,
                OrderHistoryType::MEMBER,
                $order->note
            );
        }

        return $event->getOrder();
    }

    public function notifyForCheckout(Order $order, CartData $cartData): Order
    {
        $userMail = $order->paymentData->email;

        if ($userMail) {
            $this->notifyBuyer($order, $cartData);
        }

        // Notify admins
        $this->notifyAdmins($order, $cartData);

        return $order;
    }

    /**
     * @param  Order     $order
     * @param  CartData  $cartData
     *
     * @return  void
     */
    protected function notifyBuyer(Order $order, CartData $cartData): void
    {
        $isAdmin = false;
        $this->mailer->createMessage(
            $this->trans(
                'shopgo.mail.new.order.subject.for.buyer',
                no: $order->no,
                sitename: $this->shopGo->config('shop.sitename'),
            )
        )
            ->to($order->paymentData->email)
            ->renderBody(
                'mail.order.new-order',
                compact('order', 'cartData', 'isAdmin')
            )
            ->send();
    }

    /**
     * @param  Order     $order
     * @param  CartData  $cartData
     *
     * @return  void
     */
    protected function notifyAdmins(Order $order, CartData $cartData): void
    {
        $mailNotifyService = $this->app->service(MailNotifyService::class);

        $users = $mailNotifyService->getAdminOrderNotifyReceivers();
        $isAdmin = true;

        if (count($users)) {
            $emails = $users->column('email')->dump();

            $this->mailer->createMessage(
                $this->trans(
                    'shopgo.mail.new.order.subject.for.admin',
                    no: $order->no,
                    buyer: $order->paymentData->name,
                    sitename: $this->shopGo->config('shop.sitename'),
                )
            )
                ->bcc(...$emails)
                ->renderBody(
                    'mail.order.new-order',
                    compact('order', 'cartData', 'isAdmin')
                )
                ->send();
        }
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

        if (trim($order->note)) {
            $msg .= $this->trans('shopgo.order.history.new.note', note: $order->note);
        }

        $state ??= $this->orm->mustFindOne(OrderState::class, $order->stateId);

        return $this->orderHistoryService->createHistory(
            $order,
            $state,
            OrderHistoryType::SYSTEM,
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

        // Check prices
        foreach ($items as $item) {
            if ($item->getPriceSet()['final_total']->lt('0')) {
                throw new ValidateFailException('Cannot process product item with negative prices.');
            }

            if ($item->getPriceSet()['attached_final_total']->lt('0')) {
                throw new ValidateFailException('Cannot process product item with negative prices.');
            }
        }

        foreach ($items as $item) {
            $orderItem = $this->cartItemToOrderItem($item);
            $orderItem->orderId = $order->id;

            $orderItem = $this->orm->createOne(OrderItem::class, $orderItem);

            $order->orderItems->attach($orderItem);

            $orderItems[] = $orderItem;

            foreach ($item->getAttachments() as $attachment) {
                $attachItem = $this->cartItemToOrderItem($attachment);

                $attachItem->orderId = $order->id;
                $attachItem->parentId = $orderItem->id;
                $attachItem->attachmentId = (int) $attachment->getKey();

                $attachItem = $this->orm->createOne(OrderItem::class, $attachItem);

                $orderItem->attachments->attach($attachItem);
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
            $orderTotal->orderId = $order->id;
            $orderTotal->title = $total->getLabel();
            $orderTotal->type = str_starts_with($total->getName(), 'discount')
                ? 'discount'
                : 'total';
            $orderTotal->code = $total->getName();
            $orderTotal->title = $total->getLabel();
            $orderTotal->value = $total->getPrice()->toFloat();
            $orderTotal->params = $total->getParams();
            $orderTotal->discountId = $total->getParams()['discount_id'] ?? 0;
            $orderTotal->discountType = $total->getParams()['discount_type'] ?? '';
            $orderTotal->ordering = $i;
            $orderTotal->protect = $orderTotal->type === 'total';

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
        $no = $this->orderService->createOrderNo($order->id);
        $tradeNo = $this->orderService->getPaymentNo($no, $test);

        // Save NO
        $this->orm->updateWhere(
            Order::class,
            ['no' => $no, 'payment_no' => $tradeNo],
            ['id' => $order->id]
        );

        $order->no = $no;
        $order->paymentNo = $tradeNo;

        return $order;
    }

    /**
     * @param  CartItem  $item
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
            $variant->product ?? $this->getProduct($variant->productId)
        );
        $mainVariant = $item->getMainVariant();
        $currency = $this->currencyService->getCurrentCurrency();

        $orderItem = new OrderItem();
        $orderItem->productId = $product->id;
        $orderItem->variantId = $variant->id;
        $orderItem->variantHash = $variant->hash;
        $orderItem->key = $item->getKey();
        $orderItem->title = $product->title;
        $orderItem->variantTitle = $variant->title;
        $orderItem->basePriceUnit = $variant->price;
        $orderItem->priceUnit = $item->getPriceSet()['final']->toFloat();
        $orderItem->quantity = $item->getQuantity();
        $orderItem->image = $variant->cover ?: $mainVariant->getCover();
        $orderItem->total = $item->getPriceSet()['final_total']->toFloat();
        $orderItem->priceSet = clone $item->getPriceSet();
        $orderItem->productData = [
            'product' => $product->toCollection()
                ->except(['searchIndex']),
            'variant' => $variant->toCollection()
                ->except(['searchIndex']),
            'currency' => $currency->toCollection()
                ->only(['id', 'code', 'exchangeRate', 'codeNum']),
        ];
        $orderItem->options = $variant->options;

        $data = $this->orm->extractEntity($orderItem);

        return $orderItem;
    }

    public function processPayment(Order $order, RouteUri $completeUrl)
    {
        $paymentInstance = $this->paymentService->getInstanceById($order->paymentId);

        return $paymentInstance->processCheckout($order, $completeUrl);
    }
}

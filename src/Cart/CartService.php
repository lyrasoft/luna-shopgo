<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Cart;

use Brick\Math\Exception\MathException;
use Lyrasoft\ShopGo\Cart\Price\PriceObject;
use Lyrasoft\ShopGo\Cart\Price\PriceSet;
use Lyrasoft\ShopGo\Entity\Discount;
use Lyrasoft\ShopGo\Entity\Location;
use Lyrasoft\ShopGo\Entity\Product;
use Lyrasoft\ShopGo\Entity\ProductVariant;
use Lyrasoft\ShopGo\Entity\Shipping;
use Lyrasoft\ShopGo\Enum\DiscountType;
use Lyrasoft\ShopGo\Event\AfterComputeTotalsEvent;
use Lyrasoft\ShopGo\Event\BeforeComputeTotalsEvent;
use Lyrasoft\ShopGo\Event\ComputingTotalsEvent;
use Lyrasoft\ShopGo\Event\PrepareCartDataEvent;
use Lyrasoft\ShopGo\Event\PrepareCartItemEvent;
use Lyrasoft\ShopGo\Event\PrepareProductPricesEvent;
use Lyrasoft\ShopGo\Repository\ProductVariantRepository;
use Lyrasoft\ShopGo\Service\PricingService;
use Lyrasoft\ShopGo\Service\VariantService;
use Lyrasoft\ShopGo\Shipping\ShippingService;
use Lyrasoft\ShopGo\ShopGoPackage;
use Unicorn\Selector\ListSelector;
use Windwalker\Core\Application\ApplicationInterface;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Core\Router\Navigator;
use Windwalker\DI\Attributes\Autowire;
use Windwalker\ORM\ORM;
use Windwalker\Utilities\TypeCast;

use function Windwalker\collect;

/**
 * The CartService class.
 */
class CartService
{
    use TranslatorTrait;

    public const FOR_UPDATE = 2 << 0;

    public function __construct(
        protected ApplicationInterface $app,
        protected ShopGoPackage $shopGo,
        protected Navigator $nav,
        protected ORM $orm,
        #[Autowire]
        protected ProductVariantRepository $variantRepository,
        protected VariantService $variantService,
        protected ShippingService $shippingService,
    ) {
        //
    }

    public function getCartDataForCheckout(
        int $locationId,
        int|string $shippingId,
        int|string $paymentId,
        array $options = [],
        bool $lock = false
    ): CartData {
        return $this->getCartData(
            array_merge(
                [
                    'shipping_id' => $shippingId,
                    'payment_id' => $paymentId,
                    'location_id' => $locationId,
                ],
                $options
            ),
            $lock ? static::FOR_UPDATE : 0
        );
    }

    public function getCartData(array $params = [], int $flags = 0): CartData
    {
        $cartItems = $this->getCartItems((bool) ($flags & static::FOR_UPDATE), $params);

        return $this->createCartDataFromItems($cartItems, $params, $flags);
    }

    /**
     * @param  bool   $forUpdate
     * @param  array  $params
     *
     * @return  array<CartItem>
     *
     * @throws \ReflectionException
     */
    public function getCartItems(bool $forUpdate = false, array $params = []): array
    {
        $cartStorage = $this->app->service(CartStorage::class);

        $items = $cartStorage->getStoredItems();

        $vIds = array_unique(array_column($items, 'variantId'));

        $variants = $this->variantRepository->getCartListSelector()
            ->where('product_variant.id', $vIds ?: [0])
            ->tapIf(
                $forUpdate,
                fn (ListSelector $selector) => $selector->forUpdate()
            )
            ->all(ProductVariant::class)
            ->keyBy('id');

        $cartItems = [];

        foreach ($items as $k => $storageItem) {
            /** @var ?ProductVariant $variant */
            $variant = $variants[$storageItem['variantId']] ?? null;

            if (!$variant) {
                continue;
            }

            $variant = clone $variant;

            $product = $this->orm->toEntity(Product::class, $variant->product);
            $mainVariant = $this->orm->toEntity(ProductVariant::class, $variant->main_variant);

            $quantity = (int) $storageItem['quantity'];

            $cartItem = new CartItem();
            $cartItem->setVariant($variant);
            $cartItem->setProduct($product);
            $cartItem->setMainVariant($mainVariant);
            $cartItem->setOutOfStock(VariantService::isOutOfStock($variant, $product, $quantity));
            $cartItem->setKey((string) $k);
            $cartItem->setCover($variant->getCover() ?: $mainVariant->getCover());
            $cartItem->setLink(
                (string) $product->makeLink($this->nav)
            );
            $cartItem->setQuantity($quantity);
            $cartItem->setPayload($storageItem['payload'] ?? []);
            $cartItem->setOptions($storageItem['options'] ?? []);

            $cartItem->setPriceSet($variant->getPriceSet());

            // @event
            $event = $this->shopGo->emit(
                PrepareCartItemEvent::class,
                compact(
                    'cartItem',
                    'storageItem',
                    'product',
                    'variant',
                    'mainVariant',
                    'forUpdate',
                    'params'
                )
            );

            $cartItems[] = $event->getCartItem();
        }

        return $cartItems;
    }

    /**
     * @param  iterable<CartItem>  $cartItems
     * @param  array               $params
     *
     * @return CartData
     * @throws MathException
     */
    public function createCartDataFromItems(iterable $cartItems, array $params = []): CartData
    {
        $cartData = new CartData();
        $cartData->setParams($params);

        $location = $this->orm->findOne(Location::class, $params['location_id'] ?? null ?: 0);
        $shipping = $this->orm->findOne(Shipping::class, $params['shipping_id'] ?? null ?: 0);

        $cartData->setLocation($location)->setShipping($shipping);

        $appliedDiscounts = [];
        $totals = new PriceSet();
        $total = PriceObject::create('products_total', '0');

        /** @var CartItem[] $cartItems */
        $cartItems = TypeCast::toArray($cartItems);

        foreach ($cartItems as $item) {
            if (!$item->isChecked()) {
                continue;
            }

            $total = $total->plus($item->getPriceSet()['final_total']);
        }

        $cartData->setItems(collect($cartItems));

        $finalTotal = PriceObject::create(
            'total',
            '0',
            $this->trans('shopgo.order.total.total')
        );

        // @event PrepareCartDataEvent
        $event = $this->shopGo->emit(
            PrepareCartDataEvent::class,
            compact(
                'total',
                'totals',
                'cartData',
                'appliedDiscounts',
            )
        );

        $totals = $event->getTotals();
        $cartData = $event->getCartData();
        $appliedDiscounts = $event->getAppliedDiscounts();

        // Now we have grand total, we must check discount min price.
        /** @var CartItem $cartItem */
        foreach ($cartItems as $cartItem) {
            $priceSet = $this->variantService->computeProductPriceSet(
                PrepareProductPricesEvent::CART,
                $cartItem->getProduct()->getData(),
                $cartItem->getVariant()->getData(),
                $cartItem->getMainVariant()->getData(),
                $cartItem->getPriceSet(),
                $cartItem,
            );

            if ($cartItem->isChecked()) {
                $finalTotal = $finalTotal->plus($priceSet['final_total']);
            }
        }

        $total = $finalTotal;

        // @event BeforeComputeTotalsEvent
        $event = $this->shopGo->emit(
            BeforeComputeTotalsEvent::class,
            compact(
                'total',
                'totals',
                'cartData',
                'appliedDiscounts'
            )
        );

        $total = $event->getTotal();
        $totals = $event->getTotals();
        $cartData = $event->getCartData();
        $appliedDiscounts = $event->getAppliedDiscounts();

        // @event ComputingTotalsEvent
        $event = $this->shopGo->emit(
            ComputingTotalsEvent::class,
            compact(
                'total',
                'totals',
                'cartData',
                'appliedDiscounts',
            )
        );

        $total = $event->getTotal();
        $totals = $event->getTotals();
        $cartData = $event->getCartData();
        $appliedDiscounts = $event->getAppliedDiscounts();

        // Shipping Fee
        $freeShipping = false;

        foreach ($appliedDiscounts as $discount) {
            $freeShipping = $freeShipping || $discount->isFreeShipping();
        }

        if (!$freeShipping) {
            $this->computeShippingFee($cartData, $total);
        }

        // Calc Grand Totals
        $grandTotal = $total->clone('grand_total', $this->trans('shopgo.order.total.grand.total'));

        foreach ($totals as $tt) {
            $grandTotal = $grandTotal->plus($tt);
        }

        // @event AfterComputeTotalsEvent
        $event = $this->shopGo->emit(
            AfterComputeTotalsEvent::class,
            compact(
                'total',
                'grandTotal',
                'totals',
                'cartData',
                'appliedDiscounts',
            )
        );

        $total = $event->getTotal();
        $totals = $event->getTotals();
        $grandTotal = $event->getGrandTotal();
        $cartData = $event->getCartData();
        $appliedDiscounts = $event->getAppliedDiscounts();

        $totals->prepend($total);
        $totals->set($grandTotal);

        $cartData->setTotals($totals);
        $cartData->setDiscounts($appliedDiscounts);

        $coupons = [];

        /** @var Discount $discount */
        foreach ($appliedDiscounts as $discount) {
            if ($discount->getType() === DiscountType::COUPON() || $discount->getSubtype() === 'code') {
                $coupons[] = $discount;
            }
        }

        $cartData->setCoupons($coupons);

        return $event->getCartData();
    }

    /**
     * @param  CartData    $cartData
     * @param  PriceObject $total
     *
     * @return  void
     */
    protected function computeShippingFee(CartData $cartData, PriceObject $total): void
    {
        $shipping = $cartData->getShipping();

        if (!$shipping) {
            return;
        }

        $instance = $this->shippingService->createTypeInstance($shipping);

        if (!$instance) {
            return;
        }

        $instance->computeShippingFee($cartData, $total);
    }
}

<?php

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
        CartParams $options = new CartParams(),
        bool $lock = false
    ): CartData {
        $defaultOptions = new CartParams(
            shippingId: $shippingId,
            paymentId: $paymentId,
            locationId: $locationId,
        );

        return $this->getCartData(
            $defaultOptions->withMerge($options),
            $lock ? static::FOR_UPDATE : 0
        );
    }

    public function getCartData(CartParams $params = new CartParams(), int $flags = 0): CartData
    {
        $cartItems = $this->getCartItems((bool) ($flags & static::FOR_UPDATE), $params);

        return $this->createCartDataFromItems($cartItems, $params, $flags);
    }

    /**
     * @param  bool        $forUpdate
     * @param  CartParams  $params
     *
     * @return  array<CartItem>
     *
     * @throws \ReflectionException
     */
    public function getCartItems(bool $forUpdate = false, CartParams $params = new CartParams()): array
    {
        $cartStorage = $this->app->service(CartStorage::class);

        $items = $cartStorage->getStoredItems();

        $vIds = array_unique(array_column($items, 'variantId'));

        $variants = $this->variantRepository->getCartListSelector()
            ->where('product_variant.id', $vIds ?: [0])
            ->tapIf(
                $forUpdate,
                fn(ListSelector $selector) => $selector->forUpdate()
            )
            ->limit(0)
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

            $cartItem = new CartItem(
                variant: $variant,
                mainVariant: $mainVariant,
                product: $product,
                priceSet: $variant->priceSet,
                quantity: $quantity,
                cover: $variant->cover ?: $mainVariant->cover,
                link: (string) $product->makeLink($this->nav),
                key: (string) $k,
                outOfStock: VariantService::isOutOfStock($variant, $product, $quantity),
                payload: $storageItem['payload'] ?? [],
                options: $storageItem['options'] ?? [],
            );

            // @event
            // Use emit new instance
            $event = $this->shopGo->emit(
                new PrepareCartItemEvent(
                    cartItem: $cartItem,
                    product: $product,
                    mainVariant: $mainVariant,
                    variant: $variant,
                    storageItem: $storageItem,
                    forUpdate: $forUpdate,
                    params: $params
                )
            );

            $cartItems[] = $event->cartItem;
        }

        return $cartItems;
    }

    /**
     * @param  iterable<CartItem>  $cartItems
     * @param  CartParams          $params
     *
     * @return CartData
     * @throws \ReflectionException
     */
    public function createCartDataFromItems(iterable $cartItems, CartParams $params = new CartParams()): CartData
    {
        $cartData = new CartData();
        $cartData->params = $params;

        $location = $this->orm->findOne(Location::class, $params->locationId ?? null ?: 0);
        $shipping = $this->orm->findOne(Shipping::class, $params->shippingId ?? null ?: 0);

        $cartData->location = $location;
        $cartData->shipping = $shipping;

        $appliedDiscounts = [];
        $totals = new PriceSet();
        $total = new PriceObject('products_total', '0');

        /** @var CartItem[] $cartItems */
        $cartItems = TypeCast::toArray($cartItems);

        foreach ($cartItems as $item) {
            if (!$item->isChecked()) {
                continue;
            }

            $total = $total->plus($item->priceSet['final_total']);
        }

        $cartData->items = collect($cartItems);

        $finalTotal = new PriceObject(
            'total',
            '0',
            $this->trans('shopgo.order.total.total')
        );

        // @event PrepareCartDataEvent
        $event = $this->shopGo->emit(
            new PrepareCartDataEvent(
                cartData: $cartData,
                total: $total,
                totals: $totals,
                appliedDiscounts: $appliedDiscounts
            )
        );

        $totals = $event->totals;
        $cartData = $event->cartData;
        $appliedDiscounts = $event->appliedDiscounts;

        // Now we have grand total, we must check discount min price.
        /** @var CartItem $cartItem */
        foreach ($cartItems as $cartItem) {
            $priceSet = $this->variantService->computeProductPriceSet(
                PrepareProductPricesEvent::CART,
                $cartItem->product->getData(),
                $cartItem->variant->getData(),
                $cartItem->mainVariant->getData(),
                $cartItem->priceSet,
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

        // emit with new instance
        $event = $this->shopGo->emit(
            new BeforeComputeTotalsEvent(
                cartData: $event->cartData,
                total: $event->total,
                totals: $event->totals,
                appliedDiscounts: $appliedDiscounts
            )
        );

        $total = $event->total;
        $totals = $event->totals;
        $cartData = $event->cartData;
        $appliedDiscounts = $event->appliedDiscounts;

        // @event ComputingTotalsEvent
        $event = $this->shopGo->emit(
            new ComputingTotalsEvent(
                cartData: $cartData,
                total: $total,
                totals: $totals,
                appliedDiscounts: $appliedDiscounts
            )
        );

        $total = $event->total;
        $totals = $event->totals;
        $cartData = $event->cartData;
        $appliedDiscounts = $event->appliedDiscounts;

        // Shipping Fee
        $freeShipping = false;

        foreach ($appliedDiscounts as $discount) {
            $freeShipping = $freeShipping || $discount->isFreeShipping();
        }

        if (!$freeShipping) {
            $this->computeShippingFee($cartData, $total);
        }

        // Calc Grand Totals
        $grandTotal = $total->with(
            name: 'grand_total',
            label: $this->trans('shopgo.order.total.grand.total')
        );

        foreach ($totals as $tt) {
            $grandTotal = $grandTotal->plus($tt);
        }

        // @event AfterComputeTotalsEvent
        // $event = $this->shopGo->emit(
        //     AfterComputeTotalsEvent::class,
        //     compact(
        //         'total',
        //         'grandTotal',
        //         'totals',
        //         'cartData',
        //         'appliedDiscounts',
        //     )
        // );

        // Emit and use new Event
        $event = $this->shopGo->emit(
            new AfterComputeTotalsEvent(
                cartData: $cartData,
                total: $total,
                totals: $totals,
                grandTotal: $grandTotal
            )
        );

        $total = $event->total;
        $totals = $event->totals;
        $grandTotal = $event->grandTotal;
        $cartData = $event->cartData;
        $appliedDiscounts = $event->appliedDiscounts;

        $totals->prepend($total);
        $totals->set($grandTotal);

        $cartData->totals = $totals;
        $cartData->discounts = $appliedDiscounts;

        $coupons = [];

        /** @var Discount $discount */
        foreach ($appliedDiscounts as $discount) {
            if ($discount->type === DiscountType::COUPON || $discount->subtype === 'code') {
                $coupons[] = $discount;
            }
        }

        $cartData->coupons = $coupons;

        return $event->cartData;
    }

    /**
     * @param  CartData     $cartData
     * @param  PriceObject  $total
     *
     * @return  void
     */
    protected function computeShippingFee(CartData $cartData, PriceObject $total): void
    {
        $shipping = $cartData->shipping;

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

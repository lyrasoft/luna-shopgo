<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Cart;

use Lyrasoft\ShopGo\Cart\Price\PriceObject;
use Lyrasoft\ShopGo\Cart\Price\PriceSet;
use Lyrasoft\ShopGo\Entity\Product;
use Lyrasoft\ShopGo\Entity\ProductVariant;
use Lyrasoft\ShopGo\Event\AfterComputeTotalsEvent;
use Lyrasoft\ShopGo\Event\BeforeComputeTotalsEvent;
use Lyrasoft\ShopGo\Event\PrepareCartItemEvent;
use Lyrasoft\ShopGo\Event\PrepareProductPricesEvent;
use Lyrasoft\ShopGo\Repository\ProductVariantRepository;
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

    public const INCLUDE_SHIPPING = 2 << 1;

    public const INCLUDE_COUPONS = 2 << 2;

    public function __construct(
        protected ApplicationInterface $app,
        protected ShopGoPackage $shopGo,
        protected Navigator $nav,
        protected ORM $orm,
        #[Autowire]
        protected ProductVariantRepository $variantRepository
    ) {
        //
    }

    public function getCartData(int $flags = 0): CartData
    {
        $cartItems = $this->getCartItems((bool) ($flags & static::FOR_UPDATE));

        return $this->createCartDataFromItems($cartItems);
    }

    /**
     * @return  array<CartItem>
     *
     * @throws \ReflectionException
     */
    public function getCartItems(bool $forUpdate = false): array
    {
        $cartStorage = $this->app->service(CartStorage::class);

        $items = $cartStorage->getStoredItems();

        $vIds = array_unique(array_column($items, 'variantId'));

        $variants = $this->variantRepository->getCartListSelector()
            ->where('product_variant.id', $vIds)
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

            $cartItem = new CartItem();
            $cartItem->setVariant($variant);
            $cartItem->setProduct($product);
            $cartItem->setMainVariant($mainVariant);
            $cartItem->setKey((string) $k);
            $cartItem->setCover($variant->main_variant->cover);
            $cartItem->setLink(
                (string) $product->makeLink($this->nav)
            );
            $cartItem->setQuantity((int) $storageItem['quantity']);
            $cartItem->setPayload($storageItem['payload'] ?? []);

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
                )
            );

            $cartItems[] = $event->getCartItem();
        }

        return $cartItems;
    }

    /**
     * @param  iterable<CartItem>  $cartItems
     *
     * @return CartData
     */
    public function createCartDataFromItems(iterable $cartItems): CartData
    {
        $cartData = new CartData();

        $totals = new PriceSet();
        $total = PriceObject::create(
            'total',
            '0',
            $this->trans('shopgo.order.total.total')
        );

        $grandTotal = $total->clone('grand_total', $this->trans('shopgo.order.total.grand.total'));

        $cartItems = TypeCast::toArray($cartItems);

        foreach ($cartItems as $item) {
            $total = $total->plus($item->getPriceSet()['base_total']);
            $grandTotal = $grandTotal->plus($item->getPriceSet()['final_total']);
        }

        $cartData->setItems(collect($cartItems));

        // todo: Shipping adds here
        // Add a flat shipping fee for test
        $shippingFee = PriceObject::create('shipping_fee', '200', '運費');
        $grandTotal = $grandTotal->plus($shippingFee);
        $totals->set($shippingFee);

        // @event BeforeComputeTotalsEvent
        $event = $this->shopGo->emit(
            BeforeComputeTotalsEvent::class,
            compact(
                'total',
                'grandTotal',
                'totals',
                'cartData'
            )
        );

        $total = $event->getTotal();
        $grandTotal = $event->getGrandTotal();
        $totals = $event->getTotals();
        $cartData = $event->getCartData();

        // Now we have grand total, we must check discount min price.
        /** @var CartItem $cartItem */
        foreach ($cartItems as $cartItem) {
            // Todo: @event PrepareCartItemEvent
            $product = $cartItem->getProduct()->getData();
            $variant = $cartItem->getVariant()->getData();
            $mainVariant = $cartItem->getMainVariant()->getData();
            $priceSet = $cartItem->getPriceSet();
            $appliedDiscounts = $cartItem->getDiscounts();
            $context = PrepareProductPricesEvent::CART;

            $event = $this->shopGo->emit(
                PrepareProductPricesEvent::class,
                compact(
                    'context',
                    'product',
                    'variant',
                    'mainVariant',
                    'priceSet',
                    'cartItem',
                    'appliedDiscounts',
                    'cartData',
                    'totals',
                    'total',
                    'grandTotal',
                )
            );

            $priceSet = $event->getPriceSet();

            $cartItem = $event->getCartItem();
            $cartItem->setPriceSet($priceSet);

            // /** @var ProductVariant $variant */
            // $variant = $item->getVariant()->getData();
            //
            // $item->setVariant($variant)
            //     ->setPriceSet($variant->getPriceSet());
        }


        $totals->set($total);
        // $totals = $this->computeProductsTotals($totals, $items);

        $totals->set(
            PriceObject::create('shipping_fee', '0')
                ->withLabel($this->trans('shopgo.order.total.shipping.fee'))
        );

        // @event AfterComputeTotalsEvent
        $event = $this->shopGo->emit(
            AfterComputeTotalsEvent::class,
            compact(
                'total',
                'grandTotal',
                'totals',
                'cartData'
            )
        );

        $totals = $event->getTotals();
        $totals->prepend($event->getTotal());
        $totals->set($event->getGrandTotal());

        $cartData->setTotals($totals);

        return $cartData;
    }
}

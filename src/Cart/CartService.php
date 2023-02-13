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
use Lyrasoft\ShopGo\Repository\ProductVariantRepository;
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

        foreach ($items as $k => $item) {
            /** @var ?ProductVariant $variant */
            $variant = $variants[$item['variantId']] ?? null;

            if (!$variant) {
                continue;
            }

            $product = $this->orm->toEntity(Product::class, $variant->product);

            $cartItem = new CartItem();
            $cartItem->setVariant($variant);
            $cartItem->setProduct($product);
            $cartItem->setKey($k);
            $cartItem->setCover($variant->main_variant->cover);
            $cartItem->setLink(
                (string) $product->makeLink($this->nav)
            );
            $cartItem->setQuantity((int) $item['quantity']);
            $cartItem->setIsAdditionalOf($item['isAdditionalOf'] ?? null);
            $cartItem->setPriceSet($variant->getPriceSet());

            $cartItems[] = $cartItem;
        }

        return $cartItems;
    }

    /**
     * @param  iterable<CartItem>  $items
     *
     * @return CartData
     */
    public function createCartDataFromItems(iterable $items): CartData
    {
        $cartData = new CartData();

        $totals = new PriceSet();
        $total = PriceObject::create(
            'total',
            '0',
            $this->trans('shopgo.order.total.total')
        );

        $grandTotal = $total->clone('grand_total', $this->trans('shopgo.order.total.grand.total'));

        $items = TypeCast::toArray($items);

        foreach ($items as $item) {
            $total = $total->plus($item->getPriceSet()['base_total']);
            $grandTotal = $grandTotal->plus($item->getPriceSet()['final_total']);
        }

        // Todo: @event BeforeComputeOrderTotals

        // Now we have grand total, we must check discount min price.
        foreach ($items as $item) {
            /** @var ProductVariant $variant */
            $variant = $item->getVariant()->getData();

            $item->setVariant($variant)
                ->setPriceSet($variant->getPriceSet());

            // Todo: @event PrepareCartItemEvent
        }

        $cartData->setItems(collect($items));
        $totals->set($total);
        // $totals = $this->computeProductsTotals($totals, $items);

        $totals->set(
            PriceObject::create('shipping_fee', '0')
                ->withLabel($this->trans('shopgo.order.total.shipping.fee'))
        );

        // Todo: @event AfterComputeOrderTotals

        $totals->set($grandTotal);

        $cartData->setTotals($totals);

        return $cartData;
    }
}

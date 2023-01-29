<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Cart;

use App\Cart\Price\PriceObject;
use App\Cart\Price\PriceSet;
use App\Entity\ProductVariant;
use App\Entity\Traits\ProductVariantTrait;
use Windwalker\Core\Router\Navigator;

/**
 * The CartService class.
 */
class CartService
{
    public function __construct(protected Navigator $nav)
    {
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
            '商品小計'
        );

        $grandTotal = $total->clone('grand_total', '訂單總計');

        foreach ($items as $item) {
            $total = $total->plus($item->getPriceSet()['base_total']);
            $grandTotal = $grandTotal->plus($item->getPriceSet()['final_total']);
        }

        // Now we have grand total, we must check discount min price.
        foreach ($items as $item) {
            /** @var ProductVariant $variant */
            $variant = $item->getVariant()->getData();

            $item->setVariant($variant)
                ->setPriceSet($variant->getPriceSet());
        }

        $totals->set($total);
        // $totals = $this->computeProductsTotals($totals, $items);

        $totals->set(
            PriceObject::create('shipping_fee', '0')
                ->withLabel('運費')
        );

        $totals->set($grandTotal);

        $cartData->setTotals($totals);

        return $cartData;
    }

    /**
     * @param  iterable<ProductVariantTrait>  $variants
     *
     * @return  void
     */
    public function variantsToCartItems(iterable $variants)
    {
        $items = [];

        foreach ($variants as $variant) {
            $item = new CartItem();
            $item->setVariant($variant)
                ->setPriceSet($variant->getPriceSet());
                // ->setQuantity()
        }
    }
}

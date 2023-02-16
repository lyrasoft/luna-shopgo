<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Data\Contract;

use Lyrasoft\ShopGo\Cart\CartData;
use Lyrasoft\ShopGo\Cart\CartItem;
use Lyrasoft\ShopGo\Cart\Price\PriceObject;
use Lyrasoft\ShopGo\Cart\Price\PriceSet;
use Lyrasoft\ShopGo\Entity\Discount;
use Lyrasoft\ShopGo\Entity\Product;

/**
 * Interface CartTotalsInterface
 */
interface CartTotalsInterface extends DiscountsAppliedInterface
{
    /**
     * @return CartData
     */
    public function getCartData(): CartData;

    /**
     * @param  CartData  $cartData
     *
     * @return  static  Return self to support chaining.
     */
    public function setCartData(CartData $cartData): static;

    /**
     * @return PriceSet
     */
    public function getTotals(): PriceSet;

    /**
     * @param  PriceSet  $totals
     *
     * @return  static  Return self to support chaining.
     */
    public function setTotals(PriceSet $totals): static;

    /**
     * @return PriceObject
     */
    public function getTotal(): PriceObject;

    /**
     * @param  PriceObject  $total
     *
     * @return  static  Return self to support chaining.
     */
    public function setTotal(PriceObject $total): static;

    /**
     * @return \SplObjectStorage<Discount, CartItem>
     */
    public function getMatchedItems(): \SplObjectStorage;

    /**
     * @param  \SplObjectStorage<Discount, CartItem>  $matchedItems
     *
     * @return  static  Return self to support chaining.
     */
    public function setMatchedItems(\SplObjectStorage $matchedItems): static;

    /**
     * @param  Discount  $discount
     * @param  CartItem  $cartItem
     *
     * @return  static  Return self to support chaining.
     */
    public function addMatchedItem(Discount $discount, CartItem $cartItem): static;
}

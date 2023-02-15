<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Data\Traits;

use Lyrasoft\ShopGo\Cart\CartData;
use Lyrasoft\ShopGo\Cart\Price\PriceObject;
use Lyrasoft\ShopGo\Cart\Price\PriceSet;

/**
 * Trait OrderTotalsTrait
 */
trait CartTotalsTrait
{
    public CartData $cartData;

    public PriceObject $total;

    public PriceObject $grandTotal;

    public PriceSet $totals;

    /**
     * @return CartData
     */
    public function getCartData(): CartData
    {
        return $this->cartData;
    }

    /**
     * @param  CartData  $cartData
     *
     * @return  static  Return self to support chaining.
     */
    public function setCartData(CartData $cartData): static
    {
        $this->cartData = $cartData;

        return $this;
    }

    /**
     * @return PriceSet
     */
    public function getTotals(): PriceSet
    {
        return $this->totals;
    }

    /**
     * @param  PriceSet  $totals
     *
     * @return  static  Return self to support chaining.
     */
    public function setTotals(PriceSet $totals): static
    {
        $this->totals = $totals;

        return $this;
    }

    /**
     * @return PriceObject
     */
    public function getTotal(): PriceObject
    {
        return $this->total;
    }

    /**
     * @param  PriceObject  $total
     *
     * @return  static  Return self to support chaining.
     */
    public function setTotal(PriceObject $total): static
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return PriceObject
     */
    public function getGrandTotal(): PriceObject
    {
        return $this->grandTotal;
    }

    /**
     * @param  PriceObject  $grandTotal
     *
     * @return  static  Return self to support chaining.
     */
    public function setGrandTotal(PriceObject $grandTotal): static
    {
        $this->grandTotal = $grandTotal;

        return $this;
    }
}

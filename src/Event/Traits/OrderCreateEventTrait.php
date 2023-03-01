<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Event\Traits;

use Lyrasoft\ShopGo\Cart\CartData;
use Lyrasoft\ShopGo\Cart\Price\PriceSet;
use Lyrasoft\ShopGo\Entity\Order;
use Lyrasoft\ShopGo\Entity\OrderItem;
use Lyrasoft\ShopGo\Entity\OrderTotal;

/**
 * Trait OrderCreateEventTrait
 */
trait OrderCreateEventTrait
{
    public Order $order;

    /**
     * @var PriceSet
     */
    public PriceSet $totals;

    public CartData $cartData;

    /**
     * @return Order
     */
    public function getOrder(): Order
    {
        return $this->order;
    }

    /**
     * @param  Order  $order
     *
     * @return  static  Return self to support chaining.
     */
    public function setOrder(Order $order): static
    {
        $this->order = $order;

        return $this;
    }

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
}

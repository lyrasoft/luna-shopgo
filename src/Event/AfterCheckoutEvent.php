<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Event;

use Lyrasoft\ShopGo\Cart\CartData;
use Lyrasoft\ShopGo\Entity\Order;
use Lyrasoft\ShopGo\Entity\OrderItem;
use Windwalker\Event\AbstractEvent;

/**
 * The BeforeCheckoutEvent class.
 */
class AfterCheckoutEvent extends AbstractEvent
{
    protected Order $order;

    protected CartData $cartData;

    protected array $orderItems = [];

    protected array $input = [];

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
     * @return array<OrderItem>
     */
    public function &getOrderItems(): array
    {
        return $this->orderItems;
    }

    /**
     * @param  array<OrderItem>  $orderItems
     *
     * @return  static  Return self to support chaining.
     */
    public function setOrderItems(array $orderItems): static
    {
        $this->orderItems = $orderItems;

        return $this;
    }

    /**
     * @return array
     */
    public function &getInput(): array
    {
        return $this->input;
    }

    /**
     * @param  array  $input
     *
     * @return  static  Return self to support chaining.
     */
    public function setInput(array $input): static
    {
        $this->input = $input;

        return $this;
    }
}

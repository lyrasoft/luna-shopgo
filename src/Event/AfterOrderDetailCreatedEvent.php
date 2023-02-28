<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Event;

use Lyrasoft\ShopGo\Entity\OrderHistory;
use Lyrasoft\ShopGo\Event\Traits\OrderCreateEventTrait;
use Windwalker\Data\Collection;
use Windwalker\Event\AbstractEvent;

/**
 * The AfterOrderInfoSavedEvent class.
 */
class AfterOrderDetailCreatedEvent extends AbstractEvent
{
    use OrderCreateEventTrait;

    protected Collection $orderTotals;

    protected Collection $orderItems;

    protected OrderHistory $orderHistory;

    /**
     * @return Collection
     */
    public function getOrderTotals(): Collection
    {
        return $this->orderTotals;
    }

    /**
     * @param  Collection  $orderTotals
     *
     * @return  static  Return self to support chaining.
     */
    public function setOrderTotals(Collection $orderTotals): static
    {
        $this->orderTotals = $orderTotals;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    /**
     * @param  Collection  $orderItems
     *
     * @return  static  Return self to support chaining.
     */
    public function setOrderItems(Collection $orderItems): static
    {
        $this->orderItems = $orderItems;

        return $this;
    }

    /**
     * @return OrderHistory
     */
    public function getOrderHistory(): OrderHistory
    {
        return $this->orderHistory;
    }

    /**
     * @param  OrderHistory  $orderHistory
     *
     * @return  static  Return self to support chaining.
     */
    public function setOrderHistory(OrderHistory $orderHistory): static
    {
        $this->orderHistory = $orderHistory;

        return $this;
    }
}

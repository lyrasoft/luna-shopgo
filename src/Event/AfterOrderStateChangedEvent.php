<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Event;

use Lyrasoft\ShopGo\Entity\Order;
use Lyrasoft\ShopGo\Entity\OrderState;
use Lyrasoft\ShopGo\Service\OrderStateService;
use Windwalker\Event\AbstractEvent;

/**
 * The AfterOrderStateChangedEvent class.
 */
class AfterOrderStateChangedEvent extends AbstractEvent
{
    protected Order $order;

    protected int $from;

    protected int $to = 0;

    protected ?OrderState $fromState = null;

    protected ?OrderState $toState = null;

    protected OrderStateService $orderStateService;

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
     * @return int
     */
    public function getFrom(): int
    {
        return $this->from;
    }

    /**
     * @param  int  $from
     *
     * @return  static  Return self to support chaining.
     */
    public function setFrom(int $from): static
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @return int
     */
    public function getTo(): int
    {
        return $this->to;
    }

    /**
     * @param  int  $to
     *
     * @return  static  Return self to support chaining.
     */
    public function setTo(int $to): static
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @return OrderState|null
     */
    public function getFromState(): ?OrderState
    {
        return $this->fromState;
    }

    /**
     * @param  OrderState|null  $fromState
     *
     * @return  static  Return self to support chaining.
     */
    public function setFromState(?OrderState $fromState): static
    {
        $this->fromState = $fromState;

        return $this;
    }

    /**
     * @return OrderState|null
     */
    public function getToState(): ?OrderState
    {
        return $this->toState;
    }

    /**
     * @param  OrderState|null  $toState
     *
     * @return  static  Return self to support chaining.
     */
    public function setToState(?OrderState $toState): static
    {
        $this->toState = $toState;

        return $this;
    }

    /**
     * @return OrderStateService
     */
    public function getOrderStateService(): OrderStateService
    {
        return $this->orderStateService;
    }

    /**
     * @param  OrderStateService  $orderStateService
     *
     * @return  static  Return self to support chaining.
     */
    public function setOrderStateService(OrderStateService $orderStateService): static
    {
        $this->orderStateService = $orderStateService;

        return $this;
    }
}

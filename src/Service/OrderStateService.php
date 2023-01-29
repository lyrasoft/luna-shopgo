<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Service;

use App\Entity\Order;
use App\Entity\OrderState;

use Windwalker\Data\Collection;
use Windwalker\ORM\ORM;
use Windwalker\Utilities\Cache\InstanceCacheTrait;

use function Windwalker\chronos;

/**
 * The OrderStateService class.
 */
class OrderStateService
{
    use InstanceCacheTrait;

    public function __construct(protected ORM $orm)
    {
    }

    /**
     * @return  Collection<OrderState>
     */
    public function getOrderStates(): Collection
    {
        return $this->cacheStorage['states']
            ??= $this->orm->findList(OrderState::class)->all();
    }

    public function getDefaultState(): OrderState
    {
        return $this->getOrderStates()
            ->findFirst(fn (OrderState $state) => $state->isDefault());
    }

    public function mutateOrderByState(
        Order $order,
        OrderState $state,
        \DateTimeInterface|int|string $now = 'now'
    ): Order {
        $now = chronos($now);

        if ($state->isShipped()) {
            $order->setShippedAt($now);
        }

        if ($state->isPaid()) {
            $order->setPaidAt($now);
        }

        if ($state->isDone()) {
            $order->setDoneAt($now);
        }

        if ($state->isCancel()) {
            $order->setCancelledAt($now);
        }

        if ($state->isReturned()) {
            $order->setReturnedAt($now);
        }

        if ($state->isRollback()) {
            $order->setRollbackAt($now);
        }

        return $order;
    }
}

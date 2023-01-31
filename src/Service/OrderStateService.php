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

use App\Enum\OrderHistoryType;
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

    public function changeState(Order $order, OrderState $to): Order
    {
        if ($order->getState()->getId() === $to->getId()) {
            return $order;
        }

        $order->setState($to);

        $this->mutateOrderByState($order, $to);

        $this->orm->updateOne(Order::class, $order);

        return $order;
    }

    /**
     * @return  Collection<OrderState>
     */
    public function getOrderStates(): Collection
    {
        return $this->cacheStorage['states']
            ??= $this->orm->from(OrderState::class)
                ->order('ordering', 'ASC')
                ->all(OrderState::class);
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

    public static function colorToContrast(string $color, int $sep = 200): string
    {
        [$r, $g, $b] = sscanf($color, '#%02x%02x%02x');

        $luma = $r * 0.2126 + $g * 0.7152 + $b * 0.0722;

        return $luma > $sep ? 'black' : 'white';
    }

    public static function colorToCSS(string $color, int $sep = 200): string
    {
        $contrast = static::colorToContrast($color, $sep);

        return "background-color: $color; color: $contrast;";
    }
}

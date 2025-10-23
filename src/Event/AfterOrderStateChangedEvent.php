<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Event;

use Lyrasoft\ShopGo\Entity\Order;
use Lyrasoft\ShopGo\Entity\OrderState;
use Lyrasoft\ShopGo\Service\OrderStateService;
use Windwalker\Event\AbstractEvent;
use Windwalker\Event\BaseEvent;

/**
 * The AfterOrderStateChangedEvent class.
 */
class AfterOrderStateChangedEvent extends BaseEvent
{
    public function __construct(
        public Order $order,
        public Order $oldOrder,
        public int $from,
        public int $to,
        public OrderStateService $orderStateService,
        public ?OrderState $fromState = null,
        public ?OrderState $toState = null,
    ) {
    }
}

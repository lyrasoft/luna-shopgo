<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Event;

use Lyrasoft\ShopGo\Cart\CartData;
use Lyrasoft\ShopGo\Cart\Price\PriceSet;
use Lyrasoft\ShopGo\Entity\Order;
use Lyrasoft\ShopGo\Entity\OrderHistory;
use Lyrasoft\ShopGo\Event\Traits\OrderCreateEventTrait;
use Windwalker\Data\Collection;
use Windwalker\Event\BaseEvent;

/**
 * The AfterOrderInfoSavedEvent class.
 */
class AfterOrderDetailCreatedEvent extends BaseEvent
{
    use OrderCreateEventTrait;

    /**
     * @param  Collection    $orderTotals
     * @param  Collection    $orderItems
     * @param  OrderHistory  $orderHistory
     */
    public function __construct(
        Order $order,
        PriceSet $totals,
        CartData $cartData,
        public Collection $orderTotals,
        public Collection $orderItems,
        public OrderHistory $orderHistory,
    ) {
        $this->order = $order;
        $this->totals = $totals;
        $this->cartData = $cartData;
    }
}

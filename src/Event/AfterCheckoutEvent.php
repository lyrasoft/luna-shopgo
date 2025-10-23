<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Event;

use Lyrasoft\ShopGo\Cart\CartData;
use Lyrasoft\ShopGo\Entity\Order;
use Lyrasoft\ShopGo\Entity\OrderItem;
use Windwalker\Event\BaseEvent;

/**
 * The BeforeCheckoutEvent class.
 */
class AfterCheckoutEvent extends BaseEvent
{
    public function __construct(
        public Order $order,
        public CartData $cartData,
        public array $orderItems = [],
        public array $input = [],
    ) {
    }
}

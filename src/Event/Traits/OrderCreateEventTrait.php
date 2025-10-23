<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Event\Traits;

use Lyrasoft\ShopGo\Cart\CartData;
use Lyrasoft\ShopGo\Cart\Price\PriceSet;
use Lyrasoft\ShopGo\Entity\Order;

/**
 * Trait OrderCreateEventTrait
 */
trait OrderCreateEventTrait
{
    public function __construct(
        public Order $order,
        public PriceSet $totals,
        public CartData $cartData
    ) {
    }
}

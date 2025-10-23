<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Event;

use Lyrasoft\ShopGo\Cart\CartData;
use Lyrasoft\ShopGo\Cart\Price\PriceObject;
use Lyrasoft\ShopGo\Cart\Price\PriceSet;

/**
 * The AfterComputeOrderTotalsEvent class.
 */
class AfterComputeTotalsEvent extends AbstractCartDataEvent
{
    public function __construct(
        CartData $cartData,
        PriceObject $total,
        PriceSet $totals,
        public PriceObject $grandTotal
    ) {
        parent::__construct(
            cartData: $cartData,
            total: $total,
            totals: $totals,
        );
    }
}

<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Event;

use Lyrasoft\ShopGo\Cart\CartData;
use Lyrasoft\ShopGo\Cart\Price\PriceObject;
use Lyrasoft\ShopGo\Cart\Price\PriceSet;
use Lyrasoft\ShopGo\Data\Contract\CartTotalsInterface;
use Lyrasoft\ShopGo\Data\Traits\CartDataAwareTrait;
use Windwalker\Event\BaseEvent;

/**
 * The AbstractComputeOrderTotalsEvent class.
 */
abstract class AbstractCartDataEvent extends BaseEvent implements CartTotalsInterface
{
    use CartDataAwareTrait;

    public function __construct(
        CartData $cartData,
        PriceObject $total,
        PriceSet $totals,
        array $appliedDiscounts = []
    ) {
        $this->cartData = $cartData;
        $this->total = $total;
        $this->totals = $totals;
        $this->appliedDiscounts = $appliedDiscounts;
    }
}

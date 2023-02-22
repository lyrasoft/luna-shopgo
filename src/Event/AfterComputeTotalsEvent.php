<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Event;

use Lyrasoft\ShopGo\Cart\Price\PriceObject;

/**
 * The AfterComputeOrderTotalsEvent class.
 */
class AfterComputeTotalsEvent extends AbstractCartDataEvent
{
    public PriceObject $grandTotal;

    /**
     * @return PriceObject
     */
    public function getGrandTotal(): PriceObject
    {
        return $this->grandTotal;
    }

    /**
     * @param  PriceObject  $grandTotal
     *
     * @return  static  Return self to support chaining.
     */
    public function setGrandTotal(PriceObject $grandTotal): static
    {
        $this->grandTotal = $grandTotal;

        return $this;
    }
}

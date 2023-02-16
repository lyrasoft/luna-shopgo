<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Data\Contract;

use Lyrasoft\ShopGo\Entity\Discount;

/**
 * Interface DiscountsAppliedInterface
 */
interface DiscountsAppliedInterface
{
    /**
     * @return array
     */
    public function &getAppliedDiscounts(): array;

    /**
     * @param  array  $appliedDiscounts
     *
     * @return  static  Return self to support chaining.
     */
    public function setAppliedDiscounts(array $appliedDiscounts): static;

    /**
     * @param  Discount  $discount
     *
     * @return  static  Return self to support chaining.
     */
    public function addAppliedDiscounts(Discount $discount): static;
}

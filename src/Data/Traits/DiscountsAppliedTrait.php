<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Data\Traits;

use Lyrasoft\ShopGo\Entity\Discount;

/**
 * Trait DiscountsAppliedTrait
 */
trait DiscountsAppliedTrait
{
    /**
     * @var array<Discount>
     */
    public array $appliedDiscounts = [];

    /**
     * @return array
     */
    public function &getAppliedDiscounts(): array
    {
        return $this->appliedDiscounts;
    }

    /**
     * @param  array  $appliedDiscounts
     *
     * @return  static  Return self to support chaining.
     */
    public function setAppliedDiscounts(array $appliedDiscounts): static
    {
        $this->appliedDiscounts = $appliedDiscounts;

        return $this;
    }

    /**
     * @param  Discount  $discount
     *
     * @return  static  Return self to support chaining.
     */
    public function addAppliedDiscounts(Discount $discount): static
    {
        foreach ($this->appliedDiscounts as $appliedDiscount) {
            if ($appliedDiscount->getId() === $discount->getId()) {
                return $this;
            }
        }

        $this->appliedDiscounts[] = $discount;

        return $this;
    }
}

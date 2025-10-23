<?php

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
     * @param  Discount  $discount
     *
     * @return  static  Return self to support chaining.
     */
    public function addAppliedDiscounts(Discount $discount): static
    {
        if (array_any($this->appliedDiscounts, fn(Discount $appliedDiscount) => $appliedDiscount->id === $discount->id)) {
            return $this;
        }

        $this->appliedDiscounts[] = $discount;

        return $this;
    }
}

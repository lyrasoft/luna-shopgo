<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Data\Contract;

use Lyrasoft\ShopGo\Entity\Discount;

/**
 * Interface DiscountsAppliedInterface
 */
interface DiscountsAppliedInterface
{
    /**
     * @var array<Discount>
     */
    public array $appliedDiscounts { get; set; }

    /**
     * @param  Discount  $discount
     *
     * @return  static  Return self to support chaining.
     */
    public function addAppliedDiscounts(Discount $discount): static;
}

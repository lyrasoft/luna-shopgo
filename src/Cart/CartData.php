<?php

/**
 * Part of toolstool project.
 *
 * @copyright  Copyright (C) 2022 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Cart;

use App\Cart\Price\PriceSet;
use App\Entity\Discount;
use Windwalker\Data\Collection;
use Windwalker\Data\ValueObject;

use function Windwalker\collect;

/**
 * The CartData class.
 */
class CartData extends ValueObject
{
    /**
     * @var Collection|CartItem[]
     */
    public Collection $items;

    /**
     * @var Collection|Discount[]
     */
    public Collection $discounts;

    /**
     * @var PriceSet
     */
    public PriceSet $totals;

    /**
     * @var Collection
     */
    public Collection $coupons;

    public function __construct(mixed $data = [])
    {
        parent::__construct($data);

        $this->items = collect();
        $this->discounts = collect();
        $this->totals = new PriceSet();
        $this->coupons = collect();
    }

    /**
     * @return CartItem[]|Collection
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    /**
     * @param  CartItem[]|Collection  $items
     *
     * @return  static  Return self to support chaining.
     */
    public function setItems(Collection $items): static
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @return Discount[]|Collection
     */
    public function getDiscounts(): Collection
    {
        return $this->discounts;
    }

    /**
     * @param  Discount[]|Collection  $discounts
     *
     * @return  static  Return self to support chaining.
     */
    public function setDiscounts(Collection $discounts): static
    {
        $this->discounts = $discounts;

        return $this;
    }

    /**
     * @return PriceSet
     */
    public function getTotals(): PriceSet
    {
        return $this->totals;
    }

    /**
     * @param  PriceSet  $totals
     *
     * @return  static  Return self to support chaining.
     */
    public function setTotals(PriceSet $totals): static
    {
        $this->totals = $totals;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getCoupons(): Collection
    {
        return $this->coupons;
    }

    /**
     * @param  Collection  $coupons
     *
     * @return  static  Return self to support chaining.
     */
    public function setCoupons(Collection $coupons): static
    {
        $this->coupons = $coupons;

        return $this;
    }
}

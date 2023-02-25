<?php

/**
 * Part of toolstool project.
 *
 * @copyright  Copyright (C) 2022 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Cart;

use Lyrasoft\ShopGo\Cart\Price\PriceSet;
use Lyrasoft\ShopGo\Entity\Discount;
use Lyrasoft\ShopGo\Entity\Location;
use Lyrasoft\ShopGo\Entity\ProductVariant;
use Lyrasoft\ShopGo\Entity\Shipping;
use Lyrasoft\ShopGo\Service\VariantService;
use Windwalker\Data\Collection;
use Windwalker\Data\ValueObject;

use function Windwalker\collect;

/**
 * The CartData class.
 */
class CartData extends ValueObject
{
    /**
     * @var Collection<CartItem>
     */
    public Collection $items;

    /**
     * @var Collection<Discount>
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

    public ?Location $location = null;

    public ?Shipping $shipping = null;

    public array $params = [];

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
     * @param  bool  $includeAttachments
     *
     * @return  array<int, int>
     */
    public function getTotalQuantities(bool $includeAttachments = false): array
    {
        $quantities = [];

        foreach ($this->getItems() as $item) {
            /** @var ProductVariant $variant */
            $variant = $item->getVariant()->getData();
            $quantity = $quantities[$variant->getId()] ?? 0;

            $quantity += $item->getQuantity();

            $quantities[$variant->getId()] = $quantity;

            if ($includeAttachments) {
                foreach ($item->getAttachments() as $attachment) {
                    /** @var ProductVariant $variant */
                    $variant = $attachment->getVariant()->getData();
                    $quantity = $quantities[$variant->getId()] ?? 0;

                    $quantity += ($attachment->getQuantity() * $item->getQuantity());

                    $quantities[$variant->getId()] = $quantity;
                }
            }
        }

        return $quantities;
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
    public function setDiscounts(Collection|array $discounts): static
    {
        $this->discounts = Collection::wrap($discounts);

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
     * @return Collection<Discount>
     */
    public function getCoupons(): Collection
    {
        return $this->coupons;
    }

    /**
     * @param  Collection|array  $coupons
     *
     * @return  static  Return self to support chaining.
     */
    public function setCoupons(Collection|array $coupons): static
    {
        $this->coupons = Collection::wrap($coupons);

        return $this;
    }

    /**
     * @return array
     */
    public function &getParams(): array
    {
        return $this->params;
    }

    /**
     * @param  array  $params
     *
     * @return  static  Return self to support chaining.
     */
    public function setParams(array $params): static
    {
        $this->params = $params;

        return $this;
    }

    /**
     * @return Location|null
     */
    public function getLocation(): ?Location
    {
        return $this->location;
    }

    /**
     * @param  Location|null  $location
     *
     * @return  static  Return self to support chaining.
     */
    public function setLocation(?Location $location): static
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return Shipping|null
     */
    public function getShipping(): ?Shipping
    {
        return $this->shipping;
    }

    /**
     * @param  Shipping|null  $shipping
     *
     * @return  static  Return self to support chaining.
     */
    public function setShipping(?Shipping $shipping): static
    {
        $this->shipping = $shipping;

        return $this;
    }
}

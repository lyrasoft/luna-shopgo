<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Data\Traits;

use Lyrasoft\ShopGo\Cart\Price\PriceSet;
use Lyrasoft\ShopGo\Entity\Discount;
use Lyrasoft\ShopGo\Entity\Product;
use Lyrasoft\ShopGo\Entity\ProductVariant;
use Windwalker\Data\Collection;

/**
 * Trait ProductPricingTrait
 */
trait ProductPricingTrait
{
    public Product $product;

    public ProductVariant $variant;

    public ProductVariant $mainVariant;

    public PriceSet $priceSet;

    public array $appliedDiscounts = [];

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @param  Product  $product
     *
     * @return  static  Return self to support chaining.
     */
    public function setProduct(Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return ProductVariant
     */
    public function getVariant(): ProductVariant
    {
        return $this->variant;
    }

    /**
     * @param  ProductVariant  $variant
     *
     * @return  static  Return self to support chaining.
     */
    public function setVariant(ProductVariant $variant): static
    {
        $this->variant = $variant;

        return $this;
    }

    /**
     * @return ProductVariant
     */
    public function getMainVariant(): ProductVariant
    {
        return $this->mainVariant;
    }

    /**
     * @param  ProductVariant  $mainVariant
     *
     * @return  static  Return self to support chaining.
     */
    public function setMainVariant(ProductVariant $mainVariant): static
    {
        $this->mainVariant = $mainVariant;

        return $this;
    }

    /**
     * @return PriceSet
     */
    public function getPriceSet(): PriceSet
    {
        return $this->priceSet;
    }

    /**
     * @param  PriceSet  $priceSet
     *
     * @return  static  Return self to support chaining.
     */
    public function setPriceSet(PriceSet $priceSet): static
    {
        $this->priceSet = $priceSet;

        return $this;
    }

    /**
     * @return array<Discount>
     */
    public function &getAppliedDiscounts(): array
    {
        return $this->appliedDiscounts;
    }

    /**
     * @param  array<Discount>  $appliedDiscounts
     *
     * @return  static  Return self to support chaining.
     */
    public function setAppliedDiscounts(array $appliedDiscounts): static
    {
        $this->appliedDiscounts = $appliedDiscounts;

        return $this;
    }
}

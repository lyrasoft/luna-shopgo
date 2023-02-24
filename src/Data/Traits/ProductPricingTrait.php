<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Data\Traits;

use Lyrasoft\ShopGo\Cart\Price\PriceObject;
use Lyrasoft\ShopGo\Cart\Price\PriceSet;
use Lyrasoft\ShopGo\Entity\Product;
use Lyrasoft\ShopGo\Entity\ProductVariant;

/**
 * Trait ProductPricingTrait
 */
trait ProductPricingTrait
{
    use DiscountsAppliedTrait;

    public string $context = self::PRODUCT_VIEW;

    public Product $product;

    public ProductVariant $variant;

    public ProductVariant $mainVariant;

    public PriceSet $priceSet;

    /**
     * @return string
     */
    public function getContext(): string
    {
        return $this->context;
    }

    /**
     * @param  string  $context
     *
     * @return  static  Return self to support chaining.
     */
    public function setContext(string $context): static
    {
        $this->context = $context;

        return $this;
    }

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
}

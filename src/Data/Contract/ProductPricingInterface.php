<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Data\Contract;

use Lyrasoft\ShopGo\Cart\Price\PriceObject;
use Lyrasoft\ShopGo\Cart\Price\PriceSet;
use Lyrasoft\ShopGo\Entity\Discount;
use Lyrasoft\ShopGo\Entity\Product;
use Lyrasoft\ShopGo\Entity\ProductVariant;

/**
 * Interface ProductPricingInterface
 */
interface ProductPricingInterface extends DiscountsAppliedInterface
{
    /**
     * @return string
     */
    public function getContext(): string;

    /**
     * @param  string  $context
     *
     * @return  static  Return self to support chaining.
     */
    public function setContext(string $context): static;

    /**
     * @return Product
     */
    public function getProduct(): Product;

    /**
     * @param  Product  $product
     *
     * @return  static  Return self to support chaining.
     */
    public function setProduct(Product $product): static;

    /**
     * @return ProductVariant
     */
    public function getVariant(): ProductVariant;

    /**
     * @param  ProductVariant  $variant
     *
     * @return  static  Return self to support chaining.
     */
    public function setVariant(ProductVariant $variant): static;

    /**
     * @return ProductVariant
     */
    public function getMainVariant(): ProductVariant;

    /**
     * @param  ProductVariant  $mainVariant
     *
     * @return  static  Return self to support chaining.
     */
    public function setMainVariant(ProductVariant $mainVariant): static;

    /**
     * @return PriceSet
     */
    public function getPricing(): PriceSet;

    /**
     * @param  PriceSet  $productSet
     *
     * @return  static  Return self to support chaining.
     */
    public function setPricing(PriceSet $productSet): static;


    /**
     * @return PriceObject
     */
    public function getBasePrice(): PriceObject;

    /**
     * @param  PriceObject  $basePrice
     *
     * @return  static  Return self to support chaining.
     */
    public function setBasePrice(PriceObject $basePrice): static;
}

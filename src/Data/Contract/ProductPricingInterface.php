<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Data\Contract;

use Lyrasoft\ShopGo\Cart\Price\PriceSet;
use Lyrasoft\ShopGo\Entity\Discount;
use Lyrasoft\ShopGo\Entity\Product;
use Lyrasoft\ShopGo\Entity\ProductVariant;

/**
 * Interface ProductPricingInterface
 */
interface ProductPricingInterface
{
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
    public function getPriceSet(): PriceSet;

    /**
     * @param  PriceSet  $productSet
     *
     * @return  static  Return self to support chaining.
     */
    public function setPriceSet(PriceSet $productSet): static;

    /**
     * @return array<Discount>
     */
    public function &getAppliedDiscounts(): array;

    /**
     * @param  array<Discount>  $appliedDiscounts
     *
     * @return  static  Return self to support chaining.
     */
    public function setAppliedDiscounts(array $appliedDiscounts): static;
}

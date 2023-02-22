<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Event;

use Lyrasoft\ShopGo\Cart\CartItem;
use Lyrasoft\ShopGo\Entity\Product;
use Lyrasoft\ShopGo\Entity\ProductVariant;
use Windwalker\Event\AbstractEvent;

/**
 * The PrepareCartItemEvent class.
 */
class PrepareCartItemEvent extends AbstractEvent
{
    public CartItem $cartItem;

    public array $storageItem = [];

    public Product $product;

    public ProductVariant $mainVariant;

    public ProductVariant $variant;

    public array $options = [];

    /**
     * @return CartItem
     */
    public function getCartItem(): CartItem
    {
        return $this->cartItem;
    }

    /**
     * @param  CartItem  $cartItem
     *
     * @return  static  Return self to support chaining.
     */
    public function setCartItem(CartItem $cartItem): static
    {
        $this->cartItem = $cartItem;

        return $this;
    }

    /**
     * @return array
     */
    public function getStorageItem(): array
    {
        return $this->storageItem;
    }

    /**
     * @param  array  $storageItem
     *
     * @return  static  Return self to support chaining.
     */
    public function setStorageItem(array $storageItem): static
    {
        $this->storageItem = $storageItem;

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
     * @return array
     */
    public function &getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param  array  $options
     *
     * @return  static  Return self to support chaining.
     */
    public function setOptions(array $options): static
    {
        $this->options = $options;

        return $this;
    }
}

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
use Lyrasoft\ShopGo\DTO\ProductVariantDTO;
use Lyrasoft\ShopGo\Entity\Product;
use Lyrasoft\ShopGo\Entity\ProductVariant;
use Windwalker\Data\ValueObject;

/**
 * The CartItem class.
 */
class CartItem extends ValueObject
{
    public ProductVariantDTO $variant;

    public Product $product;

    public int $quantity = 0;

    public string $cover = '';

    public string $link = '';

    public ?int $isAdditionalOf = null;

    public PriceSet $priceSet;

    public function __construct(mixed $data = [])
    {
        parent::__construct($data);

        $this->priceSet = new PriceSet();
    }

    /**
     * @return ProductVariantDTO
     */
    public function getVariant(): ProductVariantDTO
    {
        return $this->variant;
    }

    /**
     * @param  ProductVariantDTO|ProductVariant  $variant
     *
     * @return  static  Return self to support chaining.
     */
    public function setVariant(ProductVariantDTO|ProductVariant $variant): static
    {
        $this->variant = ProductVariantDTO::wrap($variant);

        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param  int  $quantity
     *
     * @return  static  Return self to support chaining.
     */
    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * getLink
     *
     * @return  string
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * setLink
     *
     * @param string $link
     *
     * @return  $this
     */
    public function setLink(string $link): static
    {
        $this->link = $link;

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
        $originTotal = $priceSet->get('origin')
            ->clone('base_total', 'Product Base total')
            ->multiply((string) $this->getQuantity());

        $priceSet->set($originTotal);

        $finalTotal = $priceSet->get('final')
            ->clone('final_total', 'Product Final Total')
            ->multiply((string) $this->getQuantity());

        $priceSet->set($finalTotal);

        $this->priceSet = $priceSet;

        return $this;
    }

    /**
     * @return string
     */
    public function getCover(): string
    {
        return $this->cover;
    }

    /**
     * @param  string  $cover
     *
     * @return  static  Return self to support chaining.
     */
    public function setCover(string $cover): static
    {
        $this->cover = $cover;

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
     * @return int|null
     */
    public function isAdditionalOf(): ?int
    {
        return $this->isAdditionalOf;
    }

    /**
     * @param  int|null  $isAdditionalOf
     *
     * @return  static  Return self to support chaining.
     */
    public function setIsAdditionalOf(?int $isAdditionalOf): static
    {
        $this->isAdditionalOf = $isAdditionalOf;

        return $this;
    }
}

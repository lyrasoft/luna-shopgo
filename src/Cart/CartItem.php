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
use Lyrasoft\ShopGo\DTO\ProductDTO;
use Lyrasoft\ShopGo\DTO\ProductVariantDTO;
use Lyrasoft\ShopGo\Entity\Product;
use Lyrasoft\ShopGo\Entity\ProductVariant;
use Windwalker\Data\ValueObject;

use function Windwalker\uid;

/**
 * The CartItem class.
 */
class CartItem extends ValueObject
{
    public ProductVariantDTO $variant;

    public ProductVariantDTO $mainVariant;

    public ProductDTO $product;

    public int $quantity = 0;

    public string $cover = '';

    public string $link = '';

    public string $key = '';

    public string $uid = '';

    public bool $outOfStock = false;

    public array $payload = [];

    public array $attachments = [];

    public PriceSet $priceSet;

    public array $discounts = [];

    public function __construct(mixed $data = [])
    {
        parent::__construct($data);

        $this->priceSet = new PriceSet();
        $this->uid = uid();
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
     * @param  bool      $calcTotals
     *
     * @return  static  Return self to support chaining.
     */
    public function setPriceSet(PriceSet $priceSet, bool $calcTotals = true): static
    {
        $this->priceSet = $priceSet;

        if ($calcTotals) {
            $this->calcTotals();
        }

        return $this;
    }

    public function calcTotals(): static
    {
        $priceSet = $this->priceSet;

        if ($priceSet->has('base')) {
            $baseTotal = $priceSet->get('base')
                ->clone('base_total', 'Product Base total')
                ->multiply((string) $this->getQuantity());

            $priceSet->set($baseTotal);
        }

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
     * @return ProductDTO
     */
    public function getProduct(): ProductDTO
    {
        return $this->product;
    }

    /**
     * @param  ProductDTO|Product  $product
     *
     * @return  static  Return self to support chaining.
     */
    public function setProduct(ProductDTO|Product $product): static
    {
        $this->product = ProductDTO::wrap($product);

        return $this;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param  string  $key
     *
     * @return  static  Return self to support chaining.
     */
    public function setKey(string $key): static
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @return array
     */
    public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * @param  array  $payload
     *
     * @return  static  Return self to support chaining.
     */
    public function setPayload(array $payload): static
    {
        $this->payload = $payload;

        return $this;
    }

    /**
     * @return array<CartItem>
     */
    public function getAttachments(): array
    {
        return $this->attachments;
    }

    /**
     * @param  array<CartItem>  $attachments
     *
     * @return  static  Return self to support chaining.
     */
    public function setAttachments(array $attachments): static
    {
        $this->attachments = $attachments;

        return $this;
    }

    /**
     * @param  CartItem  $attachment
     *
     * @return  static  Return self to support chaining.
     */
    public function addAttachment(CartItem $attachment): static
    {
        $this->attachments[] = $attachment;

        return $this;
    }

    /**
     * @return ProductVariantDTO
     */
    public function getMainVariant(): ProductVariantDTO
    {
        return $this->mainVariant;
    }

    /**
     * @param  ProductVariantDTO|ProductVariant  $mainVariant
     *
     * @return  static  Return self to support chaining.
     */
    public function setMainVariant(ProductVariantDTO|ProductVariant $mainVariant): static
    {
        $this->mainVariant = ProductVariantDTO::wrap($mainVariant);

        return $this;
    }

    /**
     * @return array
     */
    public function &getDiscounts(): array
    {
        return $this->discounts;
    }

    /**
     * @param  array  $discounts
     *
     * @return  static  Return self to support chaining.
     */
    public function setDiscounts(array $discounts): static
    {
        $this->discounts = $discounts;

        return $this;
    }

    /**
     * @return string
     */
    public function getUid(): string
    {
        return $this->uid;
    }

    /**
     * @param  string  $uid
     *
     * @return  static  Return self to support chaining.
     */
    public function setUid(string $uid): static
    {
        $this->uid = $uid;

        return $this;
    }

    /**
     * @return bool
     */
    public function isOutOfStock(): bool
    {
        return $this->outOfStock;
    }

    /**
     * @param  bool  $outOfStock
     *
     * @return  static  Return self to support chaining.
     */
    public function setOutOfStock(bool $outOfStock): static
    {
        $this->outOfStock = $outOfStock;

        return $this;
    }
}

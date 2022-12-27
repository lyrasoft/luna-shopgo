<?php

/**
* Part of starter project.
*
* @copyright  Copyright (C) 2021 __ORGANIZATION__.
* @license    __LICENSE__
*/

declare(strict_types=1);

namespace App\Entity;

use Windwalker\ORM\Attributes\AutoIncrement;
use Windwalker\ORM\Attributes\Cast;
use Windwalker\ORM\Cast\JsonCast;
use Windwalker\ORM\Attributes\Column;
use Windwalker\ORM\Attributes\EntitySetup;
use Windwalker\ORM\Attributes\PK;
use Windwalker\ORM\Attributes\Table;
use Windwalker\ORM\EntityInterface;
use Windwalker\ORM\EntityTrait;
use Windwalker\ORM\Metadata\EntityMetadata;

/**
* The OrderItem class.
*/
#[Table('order_items', 'order_item')]
class OrderItem implements EntityInterface
{
    use EntityTrait;
    #[Column('id'), PK, AutoIncrement]
    protected ?int $id = null;
    #[Column('order_id')]
    protected int $orderId = 0;
    #[Column('product_id')]
    protected int $productId = 0;
    #[Column('variant_id')]
    protected int $variantId = 0;
    #[Column('primary_product_id')]
    protected int $primaryProductId = 0;
    #[Column('primary_variant_id')]
    protected int $primaryVariantId = 0;
    #[Column('is_additional')]
    #[Cast('bool', 'int')]
    protected bool $isAdditional = false;
    #[Column('variant_hash')]
    protected string $variantHash = '';
    #[Column('title')]
    protected string $title = '';
    #[Column('variant_title')]
    protected string $variantTitle = '';
    #[Column('image')]
    protected string $image = '';
    #[Column('product_data')]
    #[Cast(JsonCast::class)]
    protected array $productData = array();
    #[Column('quantity')]
    protected int $quantity = 0;
    #[Column('price_unit')]
    protected float $priceUnit = 0.0;
    #[Column('origin_price_unit')]
    protected float $originPriceUnit = 0.0;
    #[Column('total')]
    protected float $total = 0.0;
    #[Column('options')]
    #[Cast(JsonCast::class)]
    protected array $options = array();
    #[Column('params')]
    #[Cast(JsonCast::class)]
    protected array $params = array();

    #[EntitySetup]
    public static function setup(EntityMetadata $metadata): void
    {
        //
    }
    public function getId() : ?int
    {
        return $this->id;
    }
    public function setId(?int $id) : static
    {
        $this->id = $id;
        return $this;
    }
    public function getOrderId() : int
    {
        return $this->orderId;
    }
    public function setOrderId(int $orderId) : static
    {
        $this->orderId = $orderId;
        return $this;
    }
    public function getProductId() : int
    {
        return $this->productId;
    }
    public function setProductId(int $productId) : static
    {
        $this->productId = $productId;
        return $this;
    }
    public function getVariantId() : int
    {
        return $this->variantId;
    }
    public function setVariantId(int $variantId) : static
    {
        $this->variantId = $variantId;
        return $this;
    }
    public function getPrimaryProductId() : int
    {
        return $this->primaryProductId;
    }
    public function setPrimaryProductId(int $primaryProductId) : static
    {
        $this->primaryProductId = $primaryProductId;
        return $this;
    }
    public function getPrimaryVariantId() : int
    {
        return $this->primaryVariantId;
    }
    public function setPrimaryVariantId(int $primaryVariantId) : static
    {
        $this->primaryVariantId = $primaryVariantId;
        return $this;
    }
    public function isAdditional() : bool
    {
        return $this->isAdditional;
    }
    public function setIsAdditional(bool $isAdditional) : static
    {
        $this->isAdditional = $isAdditional;
        return $this;
    }
    public function getVariantHash() : string
    {
        return $this->variantHash;
    }
    public function setVariantHash(string $variantHash) : static
    {
        $this->variantHash = $variantHash;
        return $this;
    }
    public function getTitle() : string
    {
        return $this->title;
    }
    public function setTitle(string $title) : static
    {
        $this->title = $title;
        return $this;
    }
    public function getVariantTitle() : string
    {
        return $this->variantTitle;
    }
    public function setVariantTitle(string $variantTitle) : static
    {
        $this->variantTitle = $variantTitle;
        return $this;
    }
    public function getImage() : string
    {
        return $this->image;
    }
    public function setImage(string $image) : static
    {
        $this->image = $image;
        return $this;
    }
    public function getProductData() : array
    {
        return $this->productData;
    }
    public function setProductData(array $productData) : static
    {
        $this->productData = $productData;
        return $this;
    }
    public function getQuantity() : int
    {
        return $this->quantity;
    }
    public function setQuantity(int $quantity) : static
    {
        $this->quantity = $quantity;
        return $this;
    }
    public function getPriceUnit() : float
    {
        return $this->priceUnit;
    }
    public function setPriceUnit(float $priceUnit) : static
    {
        $this->priceUnit = $priceUnit;
        return $this;
    }
    public function getOriginPriceUnit() : float
    {
        return $this->originPriceUnit;
    }
    public function setOriginPriceUnit(float $originPriceUnit) : static
    {
        $this->originPriceUnit = $originPriceUnit;
        return $this;
    }
    public function getTotal() : float
    {
        return $this->total;
    }
    public function setTotal(float $total) : static
    {
        $this->total = $total;
        return $this;
    }
    public function getOptions() : array
    {
        return $this->options;
    }
    public function setOptions(array $options) : static
    {
        $this->options = $options;
        return $this;
    }
    public function getParams() : array
    {
        return $this->params;
    }
    public function setParams(array $params) : static
    {
        $this->params = $params;
        return $this;
    }
}

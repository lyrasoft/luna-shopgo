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
use Windwalker\ORM\Attributes\Column;
use Windwalker\ORM\Attributes\EntitySetup;
use Windwalker\ORM\Attributes\PK;
use Windwalker\ORM\Attributes\Table;
use Windwalker\ORM\EntityInterface;
use Windwalker\ORM\EntityTrait;
use Windwalker\ORM\Metadata\EntityMetadata;

/**
* The ProductAttributeMap class.
*/
#[Table('product_attribute_maps', 'product_attribute_map')]
class ProductAttributeMap implements EntityInterface
{
    use EntityTrait;
    #[Column('id'), PK, AutoIncrement]
    protected ?int $id = null;
    #[Column('product_id')]
    protected int $productId = 0;
    #[Column('attribute_id')]
    protected int $attributeId = 0;
    #[Column('key')]
    protected string $key = '';
    #[Column('value')]
    protected string $value = '';
    #[Column('locale')]
    protected string $locale = '*';

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
    public function getProductId() : int
    {
        return $this->productId;
    }
    public function setProductId(int $productId) : static
    {
        $this->productId = $productId;
        return $this;
    }
    public function getAttributeId() : int
    {
        return $this->attributeId;
    }
    public function setAttributeId(int $attributeId) : static
    {
        $this->attributeId = $attributeId;
        return $this;
    }
    public function getKey() : string
    {
        return $this->key;
    }
    public function setKey(string $key) : static
    {
        $this->key = $key;
        return $this;
    }
    public function getValue() : string
    {
        return $this->value;
    }
    public function setValue(string $value) : static
    {
        $this->value = $value;
        return $this;
    }
    public function getLocale() : string
    {
        return $this->locale;
    }
    public function setLocale(string $locale) : static
    {
        $this->locale = $locale;
        return $this;
    }
}

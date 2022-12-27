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
 * The AdditionalPurchasis class.
 */
#[Table('additional_purchases', 'additional_purchasis')]
class AdditionalPurchasis implements EntityInterface
{
    use EntityTrait;

    #[Column('id'), PK, AutoIncrement]
    protected ?int $id = null;

    #[Column('attach_product_id')]
    protected int $attachProductId = 0;

    #[Column('attach_variant_id')]
    protected int $attachVariantId = 0;

    #[Column('primary_product_id')]
    protected int $primaryProductId = 0;

    #[Column('price')]
    protected float $price = 0.0;

    #[EntitySetup]
    public static function setup(EntityMetadata $metadata): void
    {
        //
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getAttachProductId(): int
    {
        return $this->attachProductId;
    }

    public function setAttachProductId(int $attachProductId): static
    {
        $this->attachProductId = $attachProductId;

        return $this;
    }

    public function getAttachVariantId(): int
    {
        return $this->attachVariantId;
    }

    public function setAttachVariantId(int $attachVariantId): static
    {
        $this->attachVariantId = $attachVariantId;

        return $this;
    }

    public function getPrimaryProductId(): int
    {
        return $this->primaryProductId;
    }

    public function setPrimaryProductId(int $primaryProductId): static
    {
        $this->primaryProductId = $primaryProductId;

        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }
}

<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Entity;

use Windwalker\ORM\Attributes\AutoIncrement;
use Windwalker\ORM\Attributes\Column;
use Windwalker\ORM\Attributes\EntitySetup;
use Windwalker\ORM\Attributes\PK;
use Windwalker\ORM\Attributes\Table;
use Windwalker\ORM\EntityInterface;
use Windwalker\ORM\EntityTrait;
use Windwalker\ORM\Metadata\EntityMetadata;

/**
 * The AdditionalPurchaseMap class.
 */
#[Table('additional_purchase_maps', 'additional_purchase_map')]
class AdditionalPurchaseMap implements EntityInterface
{
    use EntityTrait;

    #[Column('id'), PK, AutoIncrement]
    protected ?int $id = null;

    #[Column('additional_purchase_id')]
    protected int $additionalPurchaseId = 0;

    #[Column('attach_product_id')]
    protected int $attachProductId = 0;

    #[Column('attach_variant_id')]
    protected int $attachVariantId = 0;

    #[Column('target_product_id')]
    protected int $targetProductId = 0;

    #[EntitySetup]
    public static function setup(EntityMetadata $metadata): void
    {
        //
    }

    public function getAdditionalPurchaseId(): int
    {
        return $this->additionalPurchaseId;
    }

    public function setAdditionalPurchaseId(int $additionalPurchaseId): static
    {
        $this->additionalPurchaseId = $additionalPurchaseId;

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

    public function getTargetProductId(): int
    {
        return $this->targetProductId;
    }

    public function setTargetProductId(int $targetProductId): static
    {
        $this->targetProductId = $targetProductId;

        return $this;
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
}

<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Entity;

use Windwalker\ORM\Attributes\Column;
use Windwalker\ORM\Attributes\Table;
use Windwalker\ORM\EntityInterface;
use Windwalker\ORM\EntityTrait;

/**
 * The AdditionalPurchaseMap class.
 */
#[Table('additional_purchase_targets', 'additional_purchase_target')]
#[\AllowDynamicProperties]
class AdditionalPurchaseTarget implements EntityInterface
{
    use EntityTrait;

    #[Column('additional_purchase_id')]
    protected int $additionalPurchaseId = 0;

    #[Column('product_id')]
    protected int $productId = 0;

    public function getAdditionalPurchaseId(): int
    {
        return $this->additionalPurchaseId;
    }

    public function setAdditionalPurchaseId(int $additionalPurchaseId): static
    {
        $this->additionalPurchaseId = $additionalPurchaseId;

        return $this;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function setProductId(int $productId): static
    {
        $this->productId = $productId;

        return $this;
    }
}

<?php

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
    public int $additionalPurchaseId = 0;

    #[Column('product_id')]
    public int $productId = 0;
}

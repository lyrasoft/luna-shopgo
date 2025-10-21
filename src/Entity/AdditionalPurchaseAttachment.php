<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Entity;

use Lyrasoft\Luna\Attributes\Author;
use Lyrasoft\Luna\Attributes\Modifier;
use Lyrasoft\ShopGo\Enum\DiscountMethod;
use Unicorn\Enum\BasicState;
use Windwalker\Core\DateTime\Chronos;
use Windwalker\ORM\Attributes\AutoIncrement;
use Windwalker\ORM\Attributes\Cast;
use Windwalker\ORM\Attributes\CastNullable;
use Windwalker\ORM\Attributes\Column;
use Windwalker\ORM\Attributes\CreatedTime;
use Windwalker\ORM\Attributes\CurrentTime;
use Windwalker\ORM\Attributes\EntitySetup;
use Windwalker\ORM\Attributes\PK;
use Windwalker\ORM\Attributes\Table;
use Windwalker\ORM\Cast\JsonCast;
use Windwalker\ORM\EntityInterface;
use Windwalker\ORM\EntityTrait;
use Windwalker\ORM\Metadata\EntityMetadata;

/**
 * The AdditionalPurchaseAttachment class.
 */
#[Table('additional_purchase_attachments', 'additional_purchase_attachment')]
#[\AllowDynamicProperties]
class AdditionalPurchaseAttachment implements EntityInterface
{
    use EntityTrait;

    #[Column('id'), PK, AutoIncrement]
    public ?int $id = null;

    #[Column('additional_purchase_id')]
    public int $additionalPurchaseId = 0;

    #[Column('product_id')]
    public int $productId = 0;

    #[Column('variant_id')]
    public int $variantId = 0;

    #[Column('method')]
    #[Cast(DiscountMethod::class)]
    public DiscountMethod $method;

    #[Column('price')]
    public float $price = 0.0;

    #[Column('max_quantity')]
    public int $maxQuantity = 0;

    #[Column('state')]
    #[Cast('int')]
    #[Cast(BasicState::class)]
    public BasicState $state;

    #[Column('ordering')]
    public int $ordering = 0;

    #[Column('created')]
    #[CastNullable(Chronos::class)]
    #[CreatedTime]
    public ?Chronos $created = null;

    #[Column('modified')]
    #[CastNullable(Chronos::class)]
    #[CurrentTime]
    public ?Chronos $modified = null;

    #[Column('created_by')]
    #[Author]
    public int $createdBy = 0;

    #[Column('modified_by')]
    #[Modifier]
    public int $modifiedBy = 0;

    #[Column('params')]
    #[Cast(JsonCast::class)]
    public array $params = [];

    #[EntitySetup]
    public static function setup(EntityMetadata $metadata): void
    {
        //
    }
}

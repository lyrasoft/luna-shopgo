<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Entity;

use Windwalker\ORM\Attributes\AutoIncrement;
use Windwalker\ORM\Attributes\Cast;
use Windwalker\ORM\Attributes\Column;
use Windwalker\ORM\Attributes\EntitySetup;
use Windwalker\ORM\Attributes\PK;
use Windwalker\ORM\Attributes\Table;
use Windwalker\ORM\Cast\JsonCast;
use Windwalker\ORM\EntityInterface;
use Windwalker\ORM\EntityTrait;
use Windwalker\ORM\Metadata\EntityMetadata;

/**
 * The OrderTotal class.
 */
#[Table('order_totals', 'order_total')]
#[\AllowDynamicProperties]
class OrderTotal implements EntityInterface
{
    use EntityTrait;

    #[Column('id'), PK, AutoIncrement]
    public ?int $id = null;

    #[Column('order_id')]
    public int $orderId = 0;

    #[Column('discount_id')]
    public int $discountId = 0;

    #[Column('discount_type')]
    public string $discountType = '';

    #[Column('type')]
    public string $type = '';

    #[Column('title')]
    public string $title = '';

    #[Column('code')]
    public string $code = '';

    #[Column('value')]
    public float $value = 0.0;

    #[Column('ordering')]
    public int $ordering = 0;

    #[Column('protect')]
    #[Cast('bool', 'int')]
    public bool $protect = false;

    #[Column('params')]
    #[Cast(JsonCast::class)]
    public array $params = [];

    #[EntitySetup]
    public static function setup(EntityMetadata $metadata): void
    {
        //
    }
}

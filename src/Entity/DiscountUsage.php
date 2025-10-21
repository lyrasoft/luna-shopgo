<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Entity;

use DateTimeInterface;
use Windwalker\Core\DateTime\Chronos;
use Windwalker\ORM\Attributes\AutoIncrement;
use Windwalker\ORM\Attributes\Cast;
use Windwalker\ORM\Attributes\CastNullable;
use Windwalker\ORM\Attributes\Column;
use Windwalker\ORM\Attributes\EntitySetup;
use Windwalker\ORM\Attributes\PK;
use Windwalker\ORM\Attributes\Table;
use Windwalker\ORM\Cast\JsonCast;
use Windwalker\ORM\EntityInterface;
use Windwalker\ORM\EntityTrait;
use Windwalker\ORM\Metadata\EntityMetadata;

/**
 * The DiscountUsage class.
 */
// phpcs:disable
// todo: remove this when phpcs supports 8.4
#[Table('discount_usages', 'discount_usage')]
#[\AllowDynamicProperties]
class DiscountUsage implements EntityInterface
{
    use EntityTrait;

    #[Column('id'), PK, AutoIncrement]
    public ?int $id = null;

    #[Column('discount_id')]
    public int $discountId = 0;

    #[Column('order_id')]
    public int $orderId = 0;

    #[Column('type')]
    public string $type = '';

    #[Column('user_id')]
    public int $userId = 0;

    #[Column('used_at')]
    #[CastNullable(Chronos::class)]
    public ?Chronos $usedAt = null {
        set(\DateTimeInterface|string|null $value) => $this->usedAt = Chronos::tryWrap($value);
    }

    #[Column('params')]
    #[Cast(JsonCast::class)]
    public array $params = [];

    #[EntitySetup]
    public static function setup(EntityMetadata $metadata): void
    {
        //
    }
}

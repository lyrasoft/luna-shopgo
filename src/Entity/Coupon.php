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
use Windwalker\ORM\EntityInterface;
use Windwalker\ORM\EntityTrait;
use Windwalker\ORM\Metadata\EntityMetadata;

/**
 * The Coupon class.
 */
// phpcs:disable
// todo: remove this when phpcs supports 8.4
#[Table('coupons', 'coupon')]
#[\AllowDynamicProperties]
class Coupon implements EntityInterface
{
    use EntityTrait;

    #[Column('id'), PK, AutoIncrement]
    public ?int $id = null;

    #[Column('discount_id')]
    public int $discountId = 0;

    #[Column('user_id')]
    public int $userId = 0;

    #[Column('code')]
    public string $code = '';

    #[Column('used')]
    #[Cast('bool', 'int')]
    public bool $used = false;

    #[Column('used_at')]
    #[CastNullable(Chronos::class)]
    public ?Chronos $usedAt = null {
        set(\DateTimeInterface|string|null $value) => $this->usedAt = Chronos::tryWrap($value);
    }

    #[EntitySetup]
    public static function setup(EntityMetadata $metadata): void
    {
        //
    }
}

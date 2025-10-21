<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Entity;

use DateTimeInterface;
use Lyrasoft\Luna\Attributes\Author;
use Windwalker\Core\DateTime\Chronos;
use Windwalker\ORM\Attributes\AutoIncrement;
use Windwalker\ORM\Attributes\CastNullable;
use Windwalker\ORM\Attributes\Column;
use Windwalker\ORM\Attributes\EntitySetup;
use Windwalker\ORM\Attributes\PK;
use Windwalker\ORM\Attributes\Table;
use Windwalker\ORM\EntityInterface;
use Windwalker\ORM\EntityTrait;
use Windwalker\ORM\Metadata\EntityMetadata;

/**
 * The RewardHistory class.
 */
// phpcs:disable
// todo: remove this when phpcs supports 8.4
#[Table('reward_histories', 'reward_history')]
#[\AllowDynamicProperties]
class RewardHistory implements EntityInterface
{
    use EntityTrait;

    #[Column('id'), PK, AutoIncrement]
    public ?int $id = null;

    #[Column('user_id')]
    public int $userId = 0;

    #[Column('order_id')]
    public int $orderId = 0;

    #[Column('action')]
    public string $action = '';

    #[Column('points')]
    public float $points = 0.0;

    #[Column('remain')]
    public float $remain = 0.0;

    #[Column('ratio')]
    public string $ratio = '';

    #[Column('time')]
    #[CastNullable(Chronos::class)]
    public ?Chronos $time = null {
        set(\DateTimeInterface|string|null $value) => $this->time = Chronos::tryWrap($value);
    }

    #[Column('note')]
    public string $note = '';

    #[Column('created_by')]
    #[Author]
    public int $createdBy = 0;

    #[EntitySetup]
    public static function setup(EntityMetadata $metadata): void
    {
        //
    }
}

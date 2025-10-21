<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Entity;

use DateTimeInterface;
use Lyrasoft\ShopGo\Enum\OrderHistoryType;
use Lyrasoft\Luna\Attributes\Author;
use Windwalker\Core\DateTime\Chronos;
use Windwalker\ORM\Attributes\AutoIncrement;
use Windwalker\ORM\Attributes\Cast;
use Windwalker\ORM\Attributes\CastNullable;
use Windwalker\ORM\Attributes\Column;
use Windwalker\ORM\Attributes\CreatedTime;
use Windwalker\ORM\Attributes\EntitySetup;
use Windwalker\ORM\Attributes\PK;
use Windwalker\ORM\Attributes\Table;
use Windwalker\ORM\EntityInterface;
use Windwalker\ORM\EntityTrait;
use Windwalker\ORM\Metadata\EntityMetadata;

/**
 * The OrderHistory class.
 */
// phpcs:disable
// todo: remove this when phpcs supports 8.4
#[Table('order_histories', 'order_history')]
#[\AllowDynamicProperties]
class OrderHistory implements EntityInterface
{
    use EntityTrait;

    #[Column('id'), PK, AutoIncrement]
    public ?int $id = null;

    #[Column('order_id')]
    public int $orderId = 0;

    #[Column('type')]
    #[Cast(OrderHistoryType::class)]
    public OrderHistoryType $type {
        set(OrderHistoryType|string $value) => $this->type = OrderHistoryType::wrap($value);
        get => $this->type ??= OrderHistoryType::SYSTEM;
    }

    #[Column('state_id')]
    public int $stateId = 0;

    #[Column('state_color')]
    public string $stateColor = '';

    #[Column('state_text')]
    public string $stateText = '';

    #[Column('notify')]
    #[Cast('bool', 'int')]
    public bool $notify = false;

    #[Column('message')]
    public string $message = '';

    #[Column('created')]
    #[CastNullable(Chronos::class)]
    #[CreatedTime]
    public ?Chronos $created = null {
        set(\DateTimeInterface|string|null $value) => $this->created = Chronos::tryWrap($value);
    }

    #[Column('created_by')]
    #[Author]
    public int $createdBy = 0;

    #[EntitySetup]
    public static function setup(EntityMetadata $metadata): void
    {
        //
    }

    public function setState(OrderState|null $state): static
    {
        if (!$state) {
            $this->stateId = 0;
            $this->stateText = '';
            $this->stateColor = '';
        } else {
            $this->stateId = (int) $state->id;
            $this->stateText = $state->title;
            $this->stateColor = $state->color;
        }

        return $this;
    }
}

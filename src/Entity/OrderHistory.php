<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    MIT
 */

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
    public OrderHistoryType $type;

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
    public ?Chronos $created = null;

    #[Column('created_by')]
    #[Author]
    public int $createdBy = 0;

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

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function setOrderId(int $orderId): static
    {
        $this->orderId = $orderId;

        return $this;
    }

    public function getType(): OrderHistoryType
    {
        return $this->type;
    }

    public function setType(OrderHistoryType|string $type): static
    {
        $this->type = OrderHistoryType::wrap($type);

        return $this;
    }

    public function isNotify(): bool
    {
        return $this->notify;
    }

    public function setNotify(bool $notify): static
    {
        $this->notify = $notify;

        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getCreated(): ?Chronos
    {
        return $this->created;
    }

    public function setCreated(DateTimeInterface|string|null $created): static
    {
        $this->created = Chronos::wrapOrNull($created);

        return $this;
    }

    public function getCreatedBy(): int
    {
        return $this->createdBy;
    }

    public function setCreatedBy(int $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return int
     */
    public function getStateId(): int
    {
        return $this->stateId;
    }

    /**
     * @param  int  $stateId
     *
     * @return  static  Return self to support chaining.
     */
    public function setStateId(int $stateId): static
    {
        $this->stateId = $stateId;

        return $this;
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

    /**
     * @return string
     */
    public function getStateColor(): string
    {
        return $this->stateColor;
    }

    /**
     * @param  string  $stateColor
     *
     * @return  static  Return self to support chaining.
     */
    public function setStateColor(string $stateColor): static
    {
        $this->stateColor = $stateColor;

        return $this;
    }

    /**
     * @return string
     */
    public function getStateText(): string
    {
        return $this->stateText;
    }

    /**
     * @param  string  $stateText
     *
     * @return  static  Return self to support chaining.
     */
    public function setStateText(string $stateText): static
    {
        $this->stateText = $stateText;

        return $this;
    }
}

<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Entity;

use Windwalker\ORM\Attributes\AutoIncrement;
use Windwalker\ORM\Attributes\Cast;
use Windwalker\Core\DateTime\Chronos;
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
#[Table('coupons', 'coupon')]
class Coupon implements EntityInterface
{
    use EntityTrait;

    #[Column('code')]
    protected string $code = '';

    #[Column('discount_id')]
    protected int $discountId = 0;

    #[Column('id'), PK, AutoIncrement]
    protected ?int $id = null;

    #[Column('used')]
    #[Cast('bool', 'int')]
    protected bool $used = false;

    #[Column('used_at')]
    #[CastNullable(Chronos::class)]
    protected ?Chronos $usedAt = null;

    #[Column('user_id')]
    protected int $userId = 0;

    #[EntitySetup]
    public static function setup(EntityMetadata $metadata): void
    {
        //
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getDiscountId(): int
    {
        return $this->discountId;
    }

    public function setDiscountId(int $discountId): static
    {
        $this->discountId = $discountId;

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

    public function isUsed(): bool
    {
        return $this->used;
    }

    public function setUsed(bool $used): static
    {
        $this->used = $used;

        return $this;
    }

    public function getUsedAt(): ?Chronos
    {
        return $this->usedAt;
    }

    public function setUsedAt(\DateTimeInterface|string|null $usedAt): static
    {
        $this->usedAt = Chronos::wrapOrNull($usedAt);

        return $this;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): static
    {
        $this->userId = $userId;

        return $this;
    }
}

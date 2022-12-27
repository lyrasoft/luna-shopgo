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
use Windwalker\Core\DateTime\Chronos;
use Windwalker\ORM\Attributes\CastNullable;
use Windwalker\ORM\Attributes\CreatedTime;
use Lyrasoft\Luna\Attributes\Author;
use Windwalker\ORM\Attributes\CurrentTime;
use Lyrasoft\Luna\Attributes\Modifier;
use Windwalker\ORM\Attributes\Cast;
use Windwalker\ORM\Cast\JsonCast;
use Unicorn\Enum\BasicState;
use Windwalker\ORM\Attributes\Column;
use Windwalker\ORM\Attributes\EntitySetup;
use Windwalker\ORM\Attributes\PK;
use Windwalker\ORM\Attributes\Table;
use Windwalker\ORM\EntityInterface;
use Windwalker\ORM\EntityTrait;
use Windwalker\ORM\Metadata\EntityMetadata;

/**
 * The Currency class.
 */
#[Table('currencies', 'currency')]
class Currency implements EntityInterface
{
    use EntityTrait;

    #[Column('code')]
    protected string $code = '';

    #[Column('code_num')]
    protected int $codeNum = 0;

    #[Column('created')]
    #[CastNullable(Chronos::class)]
    #[CreatedTime]
    protected ?Chronos $created = null;

    #[Column('created_by')]
    #[Author]
    protected int $createdBy = 0;

    #[Column('decimal_place')]
    protected int $decimalPlace = 0;

    #[Column('decimal_point')]
    protected string $decimalPoint = '';

    #[Column('exchange_rate')]
    protected float $exchangeRate = 0.0;

    #[Column('id'), PK, AutoIncrement]
    protected ?int $id = null;

    #[Column('modified')]
    #[CastNullable(Chronos::class)]
    #[CurrentTime]
    protected ?Chronos $modified = null;

    #[Column('modified_by')]
    #[Modifier]
    protected int $modifiedBy = 0;

    #[Column('num_separator')]
    protected string $numSeparator = '';

    #[Column('params')]
    #[Cast(JsonCast::class)]
    protected array $params = [];

    #[Column('sign')]
    protected string $sign = '';

    #[Column('sign_position')]
    protected string $signPosition = '';

    #[Column('space')]
    #[Cast('bool', 'int')]
    protected bool $space = false;

    #[Column('state')]
    #[Cast('int')]
    #[Cast(BasicState::class)]
    protected BasicState $state;

    #[Column('title')]
    protected string $title = '';

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

    public function getCodeNum(): int
    {
        return $this->codeNum;
    }

    public function setCodeNum(int $codeNum): static
    {
        $this->codeNum = $codeNum;

        return $this;
    }

    public function getCreated(): ?Chronos
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface|string|null $created): static
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

    public function getDecimalPlace(): int
    {
        return $this->decimalPlace;
    }

    public function setDecimalPlace(int $decimalPlace): static
    {
        $this->decimalPlace = $decimalPlace;

        return $this;
    }

    public function getDecimalPoint(): string
    {
        return $this->decimalPoint;
    }

    public function setDecimalPoint(string $decimalPoint): static
    {
        $this->decimalPoint = $decimalPoint;

        return $this;
    }

    public function getExchangeRate(): float
    {
        return $this->exchangeRate;
    }

    public function setExchangeRate(float $exchangeRate): static
    {
        $this->exchangeRate = $exchangeRate;

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

    public function getModified(): ?Chronos
    {
        return $this->modified;
    }

    public function setModified(\DateTimeInterface|string|null $modified): static
    {
        $this->modified = Chronos::wrapOrNull($modified);

        return $this;
    }

    public function getModifiedBy(): int
    {
        return $this->modifiedBy;
    }

    public function setModifiedBy(int $modifiedBy): static
    {
        $this->modifiedBy = $modifiedBy;

        return $this;
    }

    public function getNumSeparator(): string
    {
        return $this->numSeparator;
    }

    public function setNumSeparator(string $numSeparator): static
    {
        $this->numSeparator = $numSeparator;

        return $this;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function setParams(array $params): static
    {
        $this->params = $params;

        return $this;
    }

    public function getSign(): string
    {
        return $this->sign;
    }

    public function setSign(string $sign): static
    {
        $this->sign = $sign;

        return $this;
    }

    public function getSignPosition(): string
    {
        return $this->signPosition;
    }

    public function setSignPosition(string $signPosition): static
    {
        $this->signPosition = $signPosition;

        return $this;
    }

    public function isSpace(): bool
    {
        return $this->space;
    }

    public function setSpace(bool $space): static
    {
        $this->space = $space;

        return $this;
    }

    public function getState(): BasicState
    {
        return $this->state;
    }

    public function setState(int|BasicState $state): static
    {
        $this->state = BasicState::wrap($state);

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }
}

<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Entity;

use Brick\Math\BigDecimal;
use DateTimeInterface;
use Lyrasoft\ShopGo\Cart\Price\PriceObject;
use Lyrasoft\ShopGo\Currency\CurrencyOptions;
use Lyrasoft\ShopGo\Enum\SignPosition;
use Lyrasoft\Luna\Attributes\Author;
use Lyrasoft\Luna\Attributes\Modifier;
use Lyrasoft\Luna\Entity\User;
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
 * The Currency class.
 */
// phpcs:disable
// todo: remove this when phpcs supports 8.4
#[Table('currencies', 'currency')]
#[\AllowDynamicProperties]
class Currency implements EntityInterface
{
    use EntityTrait;

    #[Column('id'), PK, AutoIncrement]
    public ?int $id = null;

    #[Column('title')]
    public string $title = '';

    #[Column('code')]
    public string $code = '';

    #[Column('code_num')]
    public int $codeNum = 0;

    #[Column('sign')]
    public string $sign = '';

    #[Column('sign_position')]
    #[Cast(SignPosition::class)]
    public SignPosition $signPosition {
        set(SignPosition|string $value) => $this->signPosition = SignPosition::wrap($value);
    }

    #[Column('decimal_place')]
    public int $decimalPlace = 0;

    #[Column('decimal_point')]
    public string $decimalPoint = '';

    #[Column('num_separator')]
    public string $numSeparator = '';

    #[Column('exchange_rate')]
    public float $exchangeRate = 0.0;

    #[Column('space')]
    #[Cast('bool', 'int')]
    public bool $space = false;

    #[Column('state')]
    #[Cast('int')]
    #[Cast(BasicState::class)]
    public BasicState $state {
        set(BasicState|int $value) => $this->state = BasicState::wrap($value);
    }

    #[Column('created')]
    #[CastNullable(Chronos::class)]
    #[CreatedTime]
    public ?Chronos $created = null {
        set(\DateTimeInterface|string|null $value) => $this->created = Chronos::tryWrap($value);
    }

    #[Column('modified')]
    #[CastNullable(Chronos::class)]
    #[CurrentTime]
    public ?Chronos $modified = null {
        set(\DateTimeInterface|string|null $value) => $this->modified = Chronos::tryWrap($value);
    }

    #[Column('created_by')]
    #[Author]
    public int $createdBy = 0;

    #[Column('modified_by')]
    #[Modifier]
    public int $modifiedBy = 0;

    #[Column('params')]
    #[Cast(JsonCast::class)]
    public array $params = [];

    public function formatPrice(mixed $num, CurrencyOptions $options = new CurrencyOptions()): string
    {
        if ($num instanceof PriceObject) {
            return $num->format($this, (bool) $options->code);
        }

        $num = (float) (string) $num;

        $negative = $num < 0;

        $num = (float) abs($num);

        $formatted = number_format(
            $num,
            $this->decimalPlace,
            $this->decimalPoint,
            $this->numSeparator,
        );

        $space = $this->space ? ' ' : '';

        $signPosition = $options->signPosition ?? $this->signPosition;

        if ($options->sign) {
            if ($signPosition === SignPosition::START) {
                $formatted = $this->sign . $space . $formatted;
            } else {
                $formatted .= $space . $this->sign;
            }
        }

        if ($negative) {
            return '-' . $formatted;
        }

        if ($options->code) {
            $formatted = $this->code . ' ' . $formatted;
        }

        return $formatted;
    }

    #[EntitySetup]
    public static function setup(EntityMetadata $metadata): void
    {
        $rm = $metadata->getRelationManager();

        $rm->manyToOne('user')
            ->targetTo(User::class, created_by: 'id');
    }

    public function getInputStep(): string
    {
        $place = $this->decimalPlace;

        if ($place === 0) {
            return '1';
        }

        return '0.' . str_repeat('0', $place - 1) . '1';
    }
}

<?php

/**
 * Part of 372 project.
 *
 * @copyright  Copyright (C) 2017 LYRASOFT.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Cart\Price;

use Brick\Math\BigDecimal;
use Brick\Math\Exception\MathException;
use Brick\Math\RoundingMode;
use Lyrasoft\ShopGo\Entity\Currency;

/**
 * The PriceObject class.
 *
 * @method PriceObject plus(mixed $price)
 * @method PriceObject minus(mixed $price)
 * @method PriceObject divide(mixed $price, int $scale = null, $roundingMode = RoundingMode::UNNECESSARY)
 * @method PriceObject exactlyDivide(mixed $price)
 * @method PriceObject multiply(mixed $price)
 * @method PriceObject remainder(mixed $price)
 * @method PriceObject power(mixed $price)
 * @method int         compare(mixed $price)
 * @method bool        eq(mixed $price)
 * @method bool        lt(mixed $price)
 * @method bool        lte(mixed $price)
 * @method bool        gt(mixed $price)
 * @method bool        gte(mixed $price)
 * @method bool        isZero()
 */
class PriceObject implements \JsonSerializable, \Stringable
{
    public const int DEFAULT_SCALE = 4;

    protected(set) BigDecimal $price {
        set(mixed $value) {
            if ($value instanceof self) {
                $value = $value->price;
            }

            $this->price = BigDecimal::of($value);
        }
    }

    /**
     * PriceObject constructor.
     *
     * @param  string  $name
     * @param  mixed   $price
     * @param  string  $label
     * @param  array   $params
     *
     * @throws MathException
     */
    public function __construct(
        protected(set) string $name,
        mixed $price,
        protected(set) string $label = '',
        protected(set) array $params = []
    ) {
        $this->price = $price;
    }

    public function withName(string $name): self
    {
        $new = clone $this;
        $new->name = $name;

        return $new;
    }

    public function withPrice(mixed $price): self
    {
        $new = clone $this;
        $new->price = $price;

        return $new;
    }

    /**
     * Method to set property label
     *
     * @param  string  $label
     *
     * @return  static  Return self to support chaining.
     */
    public function withLabel(string $label): static
    {
        $new = clone $this;
        $new->label = $label;

        return $new;
    }

    /**
     * @param  string  $name
     * @param  mixed   $value
     *
     * @return  static
     *
     * @since  0.1.1
     */
    public function withParamValue(string $name, mixed $value): static
    {
        $new = clone $this;
        $new->params[$name] = $value;

        return $new;
    }

    public function plusMultiple(mixed ...$prices): PriceObject
    {
        $new = clone $this;

        foreach ($prices as $price) {
            $new = $new->plus($price);
        }

        return $new;
    }

    public function withParams(array $params): static
    {
        $new = clone $this;
        $new->params = $params;

        return $new;
    }

    public function toString(): string
    {
        return (string) $this->price
            ->toScale(static::DEFAULT_SCALE);
    }

    public function toFloat(): float
    {
        return $this->price->toFloat();
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function format(Currency $currency, bool $addCode = false): string
    {
        $raw = $this->price->toScale(static::DEFAULT_SCALE, RoundingMode::HALF_UP)->toFloat();

        return $currency->formatPrice($raw, $addCode);
    }

    public function with(
        ?string $name = null,
        mixed $price = null,
        ?string $label = null,
        ?array $params = null
    ): static {
        $new = clone $this;

        if ($name !== null) {
            $new->name = $name;
        }

        if ($price !== null) {
            $new->price = $price;
        }

        if ($label !== null) {
            $new->label = $label;
        }

        if ($params !== null) {
            $new->params = $params;
        }

        return $new;
    }

    /**
     * __call
     *
     * @param  string  $name
     * @param  array   $args
     *
     * @return  mixed
     */
    public function __call(string $name, array $args)
    {
        $allow = [
            'plus' => 'plus',
            'minus' => 'minus',
            'divide' => 'dividedBy',
            'exactlyDivide' => 'exactlyDividedBy',
            'multiply' => 'multipliedBy',
            'remainder' => 'remainder',
            'power' => 'power',
        ];

        if (isset($allow[strtolower($name)])) {
            $function = $allow[strtolower($name)];

            $new = clone $this;
            $args = static::priceObjectsToStrings($args);

            $new->price = $new->price->$function(...$args);

            return $new;
        }

        $compares = [
            'compare' => 'compareTo',
            'eq' => 'isEqualTo',
            'lt' => 'isLessThan',
            'lte' => 'isLessThanOrEqualTo',
            'gt' => 'isGreaterThan',
            'gte' => 'isGreaterThanOrEqualTo',
            'iszero' => 'isZero',
        ];

        if (isset($compares[strtolower($name)])) {
            $function = $compares[strtolower($name)];

            $args = static::priceObjectsToStrings($args);

            return $this->price->$function(...$args);
        }

        throw new \BadMethodCallException('Method: ' . $name . ' no found in ' . static::class);
    }

    protected static function priceObjectsToStrings(array $items): array
    {
        return array_map(
            static fn($arg) => $arg instanceof PriceObject ? $arg->price : $arg,
            $items
        );
    }

    public function __clone(): void
    {
        $this->price = clone $this->price;
    }

    /**
     * toArray
     *
     * @return  array
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }

    /**
     * Specify data which should be serialized to JSON
     * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize(): mixed
    {
        $data = $this->toArray();

        $data['price'] = $data['price']->toScale(static::DEFAULT_SCALE, RoundingMode::HALF_CEILING);

        return $data;
    }
}

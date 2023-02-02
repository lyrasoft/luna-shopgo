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
use Brick\Math\RoundingMode;
use Lyrasoft\ShopGo\Entity\Currency;

/**
 * The PriceObject class.
 *
 * @property BigDecimal price
 * @property string     name
 * @property string     label
 * @property string     params
 *
 * @method PriceObject plus(string | PriceObject $price)
 * @method PriceObject minus(string | PriceObject $price)
 * @method PriceObject divide(string | PriceObject $price, int $scale = null, $roundingMode = RoundingMode::UNNECESSARY)
 * @method PriceObject exactlyDivide(string | PriceObject $price)
 * @method PriceObject multiply(string | PriceObject $price)
 * @method PriceObject remainder(string | PriceObject $price)
 * @method PriceObject power(string | PriceObject $price)
 * @method int         compare(string | PriceObject $price)
 * @method bool        eq(string | PriceObject $price)
 * @method bool        lt(string | PriceObject $price)
 * @method bool        lte(string | PriceObject $price)
 * @method bool        gt(string | PriceObject $price)
 * @method bool        gte(string | PriceObject $price)
 * @method bool        isZero()
 *
 * @since  0.1.1
 */
class PriceObject implements \JsonSerializable
{
    public const DEFAULT_SCALE = 4;

    /**
     * Property name.
     *
     * @var  string
     */
    protected string $name;

    /**
     * Property price.
     *
     * @var BigDecimal
     */
    protected BigDecimal $price;

    /**
     * Property label.
     *
     * @var string
     */
    protected string $label;

    /**
     * Property prams.
     *
     * @var  array
     */
    protected array $params = [];

    /**
     * PriceObject constructor.
     *
     * @param  string  $name
     * @param  string|BigDecimal|PriceObject  $price
     * @param  string  $label
     * @param  array  $params
     */
    public function __construct(
        string $name,
        string|BigDecimal|PriceObject $price,
        string $label = '',
        array $params = []
    ) {
        if ($price instanceof static) {
            $price = $price->getPrice();
        }

        $this->name = $name;
        $this->price = BigDecimal::of($price);
        $this->label = $label;
        $this->params = $params;
    }

    /**
     * create
     *
     * @param  string  $name
     * @param  string  $price
     * @param ?string  $label
     *
     * @return  static
     */
    public static function create(string $name, string $price, string $label = '')
    {
        return new static($name, $price, $label);
    }

    /**
     * Method to get property Name
     *
     * @return  string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Method to set property name
     *
     * @param  string  $name
     *
     * @return  static  Return self to support chaining.
     */
    public function withName(string $name): self
    {
        return $this->cloneInstance(function (PriceObject $new) use ($name) {
            $new->name = $name;
        });
    }

    /**
     * Method to get property Price
     *
     * @return  BigDecimal
     */
    public function getPrice(): BigDecimal
    {
        return $this->price;
    }

    /**
     * Method to set property price
     *
     * @param  string|BigDecimal  $price
     *
     * @return  static  Return self to support chaining.
     */
    public function withPrice(string|BigDecimal $price): self
    {
        return $this->cloneInstance(function (PriceObject $new) use ($price) {
            $new->price = $price instanceof BigDecimal ? $price : BigDecimal::of($price);
        });
    }

    /**
     * Method to get property Label
     *
     * @return  string
     */
    public function getLabel(): string
    {
        return $this->label;
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
        return $this->cloneInstance(function (PriceObject $new) use ($label) {
            $new->label = $label;
        });
    }

    /**
     * getParamValue
     *
     * @param  string  $name
     *
     * @return  mixed|null
     *
     * @since  0.1.1
     */
    public function getParamValue(string $name): mixed
    {
        return $this->params[$name] ?? null;
    }

    /**
     * withParamValue
     *
     * @param  string  $name
     * @param  mixed   $value
     *
     * @return  static
     *
     * @since  0.1.1
     */
    public function withParamValue(string $name, mixed $value): static
    {
        return $this->cloneInstance(function (PriceObject $new) use ($name, $value) {
            $new->params[$name] = $value;
        });
    }

    /**
     * getParams
     *
     * @return  array
     *
     * @since  0.1.1
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * widthParams
     *
     * @param  array  $params
     *
     * @return  PriceObject
     *
     * @since  0.1.1
     */
    public function widthParams(array $params): static
    {
        return $this->cloneInstance(function (PriceObject $new) use ($params) {
            $new->params = $params;
        });
    }

    public function toString(): string
    {
        return $this->getPrice()
            ->toScale(static::DEFAULT_SCALE)
            ->__toString();
    }

    public function toFloat(): float
    {
        return $this->getPrice()->toFloat();
    }

    /**
     * __toString
     *
     * @return  string
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    public function format(Currency $currency, bool $addCode = false): string
    {
        $raw = $this->getPrice()->toScale(static::DEFAULT_SCALE, RoundingMode::HALF_UP)->toFloat();

        return $currency->formatPrice($raw, $addCode);
    }

    public function clone(string $name, string $label = ''): static
    {
        return $this->withName($name)->withLabel($label);
    }

    /**
     * cloneInstance
     *
     * @param  callable  $callback
     *
     * @return  static
     */
    protected function cloneInstance(callable $callback): static
    {
        $new = clone $this;

        $callback($new);

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

            return $this->cloneInstance(function (PriceObject $new) use ($function, $args) {
                $args = array_map(
                    static fn($arg) => $arg instanceof PriceObject ? $arg->getPrice() : $arg,
                    $args
                );

                $new->price = $new->price->$function(...$args);
            });
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

            return $this->price->$function(...$args);
        }

        throw new \BadMethodCallException('Method: ' . $name . ' no found in ' . static::class);
    }

    /**
     * __get
     *
     * @param  string  $name
     *
     * @return  string
     */
    public function __get($name)
    {
        return $this->$name;
    }

    /**
     * __set
     *
     * @param  string  $name
     * @param  mixed   $value
     *
     * @return  void
     */
    public function __set(string $name, mixed $value)
    {
        if (!property_exists($this, $name)) {
            throw new \LogicException('Property ' . $name . ' not exists.');
        }

        $this->$name = (string) $value;
    }

    /**
     * When an object is cloned, PHP 5 will perform a shallow copy of all of the object's properties.
     * Any properties that are references to other variables, will remain references.
     * Once the cloning is complete, if a __clone() method is defined,
     * then the newly created object's __clone() method will be called, to allow any necessary properties that need to
     * be changed. NOT CALLABLE DIRECTLY.
     *
     * @return mixed
     * @link http://php.net/manual/en/language.oop5.cloning.php
     */
    public function __clone()
    {
        $this->price = clone $this->price;
    }

    /**
     * Method to set property price
     *
     * @param  string  $price
     *
     * @return  static  Return self to support chaining.
     */
    public function setPrice(string $price): static
    {
        $this->price = BigDecimal::of($price);

        return $this;
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
        return $this->toArray();
    }
}

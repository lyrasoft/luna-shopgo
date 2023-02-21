<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Service;

use Brick\Math\BigDecimal;
use Lyrasoft\ShopGo\Cart\Price\PriceObject;
use Lyrasoft\ShopGo\Cart\Price\PriceSet;
use Lyrasoft\ShopGo\Entity\Discount;
use Lyrasoft\ShopGo\Enum\DiscountMethod;
use Lyrasoft\ShopGo\Traits\CurrencyAwareTrait;

/**
 * The PricingService class.
 */
class PricingService
{
    use CurrencyAwareTrait;

    public static function pricingByMethod(
        mixed $origin,
        mixed $modify,
        DiscountMethod|string $method,
        ?BigDecimal &$diff = null,
    ): BigDecimal {
        /** @var DiscountMethod $method */
        $method = DiscountMethod::wrap($method);
        $diff = BigDecimal::of(0);

        if ($method === DiscountMethod::NONE()) {
            return BigDecimal::of((string) $origin);
        }

        $origin = BigDecimal::of((string) $origin);
        $modify = BigDecimal::of((string) $modify);

        if ($method === DiscountMethod::FIXED()) {
            // New price should not greater than origin.
            if ($modify->isGreaterThan($origin)) {
                return $origin;
            }

            $diff = $origin->minus($modify);
            return $modify;
        }

        if ($method === DiscountMethod::OFFSETS()) {
            $newPrice = $origin->plus($modify);

            if ($newPrice->isLessThan(0)) {
                $newPrice = BigDecimal::of(0);
            }

            $diff = $newPrice->minus($origin);

            return $newPrice;
        }

        $newPrice = $origin->dividedBy(100, PriceObject::DEFAULT_SCALE)->multipliedBy($modify);

        $diff = $newPrice->minus($origin);

        return $newPrice;
    }

    public static function pricingByDiscount(
        mixed $price,
        Discount $discount,
        ?BigDecimal &$diff = null,
    ): BigDecimal {
        return static::pricingByMethod($price, $discount->getPrice(), $discount->getMethod(), $diff);
    }

    public function pricingByMethodAndFormat(
        mixed $price,
        mixed $offsets,
        DiscountMethod|string $method,
        bool $addCode = false
    ): string {
        $price = static::pricingByMethod($price, $offsets, $method);

        return $this->formatPrice($price, $addCode);
    }

    public static function calcAmount(mixed $base, iterable $offsetsList): BigDecimal
    {
        $amount = BigDecimal::of((string) $base);

        foreach ($offsetsList as $tt) {
            $amount = $amount->plus((string) $tt);
        }

        return $amount;
    }
}

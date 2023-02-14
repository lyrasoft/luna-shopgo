<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Service;

use Brick\Math\BigDecimal;
use Lyrasoft\ShopGo\Cart\Price\PriceObject;
use Lyrasoft\ShopGo\Enum\DiscountMethod;
use Lyrasoft\ShopGo\Traits\CurrencyAwareTrait;

/**
 * The PricingService class.
 */
class PricingService
{
    use CurrencyAwareTrait;

    public static function pricingByMethod(
        PriceObject|BigDecimal|string|float $price,
        PriceObject|BigDecimal|string|float $offsets,
        DiscountMethod|string $method
    ): BigDecimal {
        /** @var DiscountMethod $method */
        $method = DiscountMethod::wrap($method);

        $price = BigDecimal::of((string) $price);
        $offsets = BigDecimal::of((string) $offsets);

        if ($method === DiscountMethod::FIXED()) {
            return $offsets;
        }

        if ($method === DiscountMethod::OFFSETS()) {
            return $price->plus($offsets);
        }

        return $price->dividedBy(100, PriceObject::DEFAULT_SCALE)->multipliedBy($offsets);
    }

    public function pricingByMethodAndFormat(
        PriceObject|BigDecimal|string|float $price,
        PriceObject|BigDecimal|string|float $offsets,
        DiscountMethod|string $method,
        bool $addCode = false
    ): string {
        $price = static::pricingByMethod($price, $offsets, $method);

        return $this->formatPrice($price, $addCode);
    }
}

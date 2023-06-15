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
use Brick\Math\RoundingMode;
use Lyrasoft\ShopGo\Cart\Price\PriceObject;
use Lyrasoft\ShopGo\Cart\Price\PriceSet;
use Lyrasoft\ShopGo\Entity\Discount;
use Lyrasoft\ShopGo\Enum\DiscountMethod;
use Lyrasoft\ShopGo\Traits\CurrencyAwareTrait;
use Windwalker\Core\Application\ApplicationInterface;
use Windwalker\Utilities\Cache\InstanceCacheTrait;

/**
 * The PricingService class.
 */
class PricingService
{
    use InstanceCacheTrait;

    public function __construct(protected ApplicationInterface $app)
    {
    }

    public function pricingByMethod(
        mixed $origin,
        mixed $modify,
        DiscountMethod|string $method,
        ?BigDecimal &$diff = null,
    ): BigDecimal {
        $scale = $this->getScale();

        /** @var DiscountMethod $method */
        $method = DiscountMethod::wrap($method);
        $diff = BigDecimal::of(0);

        if ($method === DiscountMethod::NONE()) {
            return BigDecimal::of((string) $origin)
                ->toScale($scale, RoundingMode::HALF_UP);
        }

        $origin = BigDecimal::of((string) $origin)->toScale($scale, RoundingMode::HALF_UP);
        $modify = BigDecimal::of((string) $modify)->toScale($scale, RoundingMode::HALF_UP);

        if ($method === DiscountMethod::FIXED()) {
            // New price should not greater than origin.
            if ($modify->isGreaterThan($origin)) {
                return $origin;
            }

            $diff = $origin->minus($modify);
            return $modify;
        }

        if ($method === DiscountMethod::OFFSETS()) {
            $newPrice = $origin->plus($modify)
                ->toScale($scale, RoundingMode::HALF_UP);

            if ($newPrice->isLessThan(0)) {
                $newPrice = BigDecimal::of(0);
            }

            $diff = $newPrice->minus($origin);

            return $newPrice;
        }

        $newPrice = $origin->dividedBy(
            100,
            PriceObject::DEFAULT_SCALE,
            RoundingMode::HALF_CEILING
        )
            ->multipliedBy($modify)
            ->toScale($scale, RoundingMode::HALF_UP);

        $diff = $newPrice->minus($origin);

        return $newPrice;
    }

    public function pricingByDiscount(
        mixed $price,
        Discount $discount,
        ?BigDecimal &$diff = null,
    ): BigDecimal {
        return $this->pricingByMethod($price, $discount->getPrice(), $discount->getMethod(), $diff);
    }

    public function pricingByMethodAndFormat(
        mixed $price,
        mixed $offsets,
        DiscountMethod|string $method,
        bool $addCode = false
    ): string {
        $price = $this->pricingByMethod($price, $offsets, $method);

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

    /**
     * @return  int
     */
    protected function getScale(): int
    {
        return $this->cacheStorage['scale'] ??= $this->app->service(CurrencyService::class)
            ->getMainCurrency()
            ->getDecimalPlace();
    }
}

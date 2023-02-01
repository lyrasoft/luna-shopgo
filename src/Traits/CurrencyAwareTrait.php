<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Traits;

use Lyrasoft\ShopGo\Cart\Price\PriceObject;
use Lyrasoft\ShopGo\Entity\Currency;
use Lyrasoft\ShopGo\Service\CurrencyService;
use Windwalker\DI\Attributes\Inject;

/**
 * Trait CurrencyAwareTrait
 */
trait CurrencyAwareTrait
{
    #[Inject]
    protected CurrencyService $currencyService;

    public function formatPrice(
        float|PriceObject $price,
        Currency|int|string|null $currency = null,
        bool $addCode = false
    ): string {
        return $this->currencyService->format($price, $currency, $addCode);
    }

    public function getMainCurrency(): Currency
    {
        return $this->currencyService->getMainCurrency();
    }

    public function findCurrencyBy(string|int $condition): Currency
    {
        return $this->currencyService->findCurrencyBy($condition);
    }
}

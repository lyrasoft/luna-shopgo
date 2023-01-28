<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Service;

use App\Cart\Price\PriceObject;
use App\Entity\Currency;
use Lyrasoft\Luna\Services\ConfigService;
use Windwalker\Data\Collection;
use Windwalker\ORM\ORM;
use Windwalker\Utilities\Cache\InstanceCacheTrait;

/**
 * The CurrencyService class.
 */
class CurrencyService
{
    use InstanceCacheTrait;

    public function __construct(protected ORM $orm, protected ConfigService $configService)
    {
    }

    public function format(
        float|PriceObject $num,
        Currency|int|string|null $currency = null,
        bool $addCode = false
    ): string {
        if (!$currency instanceof Currency) {
            if ($currency === null) {
                $currency = $this->getMainCurrency();
            } else {
                $currency = $this->findCurrencyBy($currency);
            }
        }

        return $currency->formatPrice($num, $addCode);
    }

    public static function formatByCurrency(float $num, Currency $currency, bool $addCode = false): string
    {
        return $currency->formatPrice($num, $addCode);
    }

    public function getMainCurrency(): Currency
    {
        return $this->once(
            'main.currency',
            function () {
                $mainCurrencyId = (int) $this->getConfig()->get('currency_main');

                $mainCurrency = $this->getCurrencies()
                    ->findFirst(fn(Currency $currency) => $currency->getId() === $mainCurrencyId);

                if (!$mainCurrency) {
                    throw new \RuntimeException('Main Currency not found.');
                }

                return $mainCurrency;
            }
        );
    }

    public function findCurrencyBy(string|int $condition): Currency
    {
        $currencies = $this->getCurrencies();

        if (is_string($condition)) {
            $currency = $currencies->findFirst(fn(Currency $currency) => $currency->getCode() === $condition);

            if ($currency) {
                return $currency;
            }
        }

        $currency = $currencies->findFirst(fn(Currency $currency) => $currency->getId() === (int) $condition);

        if (!$currency) {
            throw new \RuntimeException('Currency not found.');
        }

        return $currency;
    }

    /**
     * @return  Collection<Currency>
     */
    public function getCurrencies(): Collection
    {
        return $this->once(
            'currencies',
            fn() => $this->orm->from(Currency::class)
                ->where('state', 1)
                ->all(Currency::class)
        );
    }

    public function getConfig(): Collection
    {
        return $this->configService->getConfig('shopgo_shop');
    }
}

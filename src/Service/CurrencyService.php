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
use Lyrasoft\Luna\LunaPackage;
use Lyrasoft\ShopGo\Cart\Price\PriceObject;
use Lyrasoft\ShopGo\Entity\Currency;
use Lyrasoft\ShopGo\ShopGoPackage;
use Windwalker\Core\Application\ApplicationInterface;
use Windwalker\Core\State\AppState;
use Windwalker\Data\Collection;
use Windwalker\ORM\ORM;
use Windwalker\Session\Session;
use Windwalker\Utilities\Cache\InstanceCacheTrait;

/**
 * The CurrencyService class.
 */
class CurrencyService
{
    use InstanceCacheTrait;

    public function __construct(
        protected ORM $orm,
        protected ShopGoPackage $shopGo,
        protected ApplicationInterface $app,
    ) {
    }

    public function exchangeFromMainCurrency(mixed $num, Currency $toCurrency): BigDecimal
    {
        $n = BigDecimal::of((string) $num);

        if ($toCurrency->getExchangeRate() === 1.0) {
            return $n;
        }

        return $n->multipliedBy($toCurrency->getExchangeRate());
    }

    public function format(
        mixed $num,
        Currency|int|string|null $currency = null,
        bool $addCode = false
    ): string {
        if (!$currency instanceof Currency) {
            if ($currency === null) {
                $currency = $this->getCurrentCurrency();
            } else {
                $currency = $this->findCurrencyBy($currency);
            }
        }

        $num = $this->exchangeFromMainCurrency($num, $currency);

        return $currency->formatPrice($num, $addCode);
    }

    public function formatWithCode(
        mixed $num,
        Currency|int|string|null $currency = null
    ): string {
        return $this->format($num, null, true);
    }

    public static function formatByCurrency(
        PriceObject|BigDecimal|string|float $num,
        Currency $currency,
        bool $addCode = false
    ): string {
        return $currency->formatPrice($num, $addCode);
    }

    public function getCurrentCurrency(): Currency
    {
        if ($this->app->getClientType() === 'console') {
            return $this->getMainCurrency();
        }

        $luna = $this->app->service(LunaPackage::class);

        if ($luna->isAdmin()) {
            return $this->getMainCurrency();
        }

        $session = $this->app->service(AppState::class);

        $code = $session->get('current_currency');

        if (!$code) {
            return $this->getMainCurrency();
        }

        return $this->findCurrencyBy($code);
    }

    public function getMainCurrency(): Currency
    {
        return $this->once(
            'main.currency',
            function () {
                $mainCurrency = $this->shopGo->config('currency.main');

                $mainCurrency = $this->getCurrencies()
                    ->findFirst(
                        function (Currency $currency) use ($mainCurrency) {
                            if (is_string($mainCurrency)) {
                                return $currency->getCode() === $mainCurrency;
                            }

                            return $currency->getId() === $mainCurrency;
                        }
                    );

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

        if (is_numeric($condition)) {
            $currency = $currencies->findFirst(fn(Currency $currency) => $currency->getId() === (int) $condition);
        } else {
            $currency = $currencies->findFirst(fn(Currency $currency) => $currency->getCode() === $condition);
        }

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

    public function rememberCurrentCurrency(string|Currency $currency): void
    {
        $state = $this->app->service(AppState::class);

        if ($currency instanceof Currency) {
            $currency = $currency->getCode();
        }

        $state->remember('current_currency', $currency);
    }
}

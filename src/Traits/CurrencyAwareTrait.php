<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Traits;

use Brick\Math\BigDecimal;
use Lyrasoft\ShopGo\Cart\Price\PriceObject;
use Lyrasoft\ShopGo\Currency\CurrencyOptions;
use Lyrasoft\ShopGo\Entity\Currency;
use Lyrasoft\ShopGo\Currency\CurrencyResolver;
use Windwalker\DI\Attributes\Inject;

/**
 * Trait CurrencyAwareTrait
 */
trait CurrencyAwareTrait
{
    #[Inject]
    protected CurrencyResolver $currencyResolver;

    public function useCurrencyResolver(?CurrencyOptions $options = null): CurrencyResolver
    {
        if ($options === null) {
            return $this->currencyResolver;
        }

        return $this->currencyResolver->withUseOptions($options);
    }

    public function formatPrice(
        mixed $price,
        CurrencyOptions $options = new CurrencyOptions(),
    ): string {
        return $this->currencyResolver->format($price, null, $options);
    }

    public function getMainCurrency(): Currency
    {
        return $this->currencyResolver->getMainCurrency();
    }

    public function findCurrencyBy(string|int $condition): Currency
    {
        return $this->currencyResolver->findCurrencyBy($condition);
    }

    public function getMainInputStep(): string
    {
        return $this->getMainCurrency()->getInputStep();
    }
}

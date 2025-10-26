<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Component;

use Closure;
use Lyrasoft\ShopGo\Entity\Currency;
use Lyrasoft\ShopGo\Currency\CurrencyResolver;
use Windwalker\Core\Edge\Attribute\EdgeComponent;
use Windwalker\Edge\Component\AbstractComponent;
use Windwalker\Utilities\Attributes\Prop;

#[EdgeComponent('currency-dropdown')]
class CurrencyDropdownComponent extends AbstractComponent
{
    #[Prop]
    public string $tag = 'div';

    #[Prop]
    public string $buttonClass = '';

    #[Prop]
    public string $menuClass = '';

    public function __construct(protected CurrencyResolver $currencyResolver)
    {
    }

    public function render(): Closure|string
    {
        return 'components.currency-dropdown';
    }

    public function data(): array
    {
        $currentCurrency = $this->currencyResolver->getCurrentCurrency();

        $currencies = $this->currencyResolver->getCurrencies()
            ->filter(fn(Currency $currency) => $currency->id !== $currentCurrency->id);

        return [
            ...parent::data(),
            ...compact('currentCurrency', 'currencies'),
        ];
    }
}

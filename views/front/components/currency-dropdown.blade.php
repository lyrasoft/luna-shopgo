<?php

declare(strict_types=1);

namespace App\view;

/**
 * Global variables
 * --------------------------------------------------------------
 * @var $app       AppContext      Application context.
 * @var $vm        object          The view model object.
 * @var $uri       SystemUri       System Uri information.
 * @var $chronos   ChronosService  The chronos datetime service.
 * @var $nav       Navigator       Navigator object to build route.
 * @var $asset     AssetService    The Asset manage service.
 * @var $lang      LangService     The language translation service.
 */

use Lyrasoft\ShopGo\Entity\Currency;
use Lyrasoft\ShopGo\Service\CurrencyService;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;
use Windwalker\Edge\Component\ComponentAttributes;

/**
 * @var $attributes ComponentAttributes
 */

$currencyService = $app->service(CurrencyService::class);
$currentCurrency = $currencyService->getCurrentCurrency();

$currencies = $currencyService->getCurrencies()
    ->filter(fn(Currency $currency) => $currency->getId() !== $currentCurrency->getId());

$attributes = $attributes->class('dropdown');

$props = $attributes->props(
    'tag',
);

$tag = $props->tag ?: 'li';
?>
<{{ $tag }} {!! $attributes !!}>
    <a href="javascript://" class="nav-link dropdown-toggle"
        data-bs-toggle="dropdown">
        {{ $currentCurrency->getTitle() }}
    </a>
    <div class="dropdown-menu">
        @foreach ($currencies as $currency)
            <a class="dropdown-item" href="javascript://"
                onclick="u.form().post('{{ $nav->to('currency_switch') }}', { code: '{{ $currency->getCode() }}' })">
                {{ $currency->getTitle() }}
            </a>
        @endforeach
    </div>
</{{ $tag }}>

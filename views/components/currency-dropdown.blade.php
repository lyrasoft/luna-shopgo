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

$attributes = $attributes->class('dropdown');

$props = $attributes->props(
    'tag',
);

$tag = $props->tag ?: 'div';
?>
<{{ $tag }} {!! $attributes !!}>
    <a href="javascript://" class="dropdown-toggle {{ $buttonClass }}"
        data-bs-toggle="dropdown">
        {{ $currentCurrency->title }}
    </a>
    <div class="dropdown-menu {{ $menuClass }}">
        @foreach ($currencies as $currency)
            <a class="dropdown-item" href="javascript://"
                uni-currency-switch="{{ $currency->code }}">
                {{ $currency->title }}
            </a>
        @endforeach
    </div>
</{{ $tag }}>

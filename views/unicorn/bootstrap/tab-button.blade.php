<?php

declare(strict_types=1);

namespace App\View;

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

use function Windwalker\uid;

/**
 * @var $attributes ComponentAttributes
 */

$props = $attributes->props(
    'active',
    'href',
    'target'
);

$id ??= 'c-tab-button-' . uid();
$href ??= 'javascript://';
$active = $props->active !== null;

$attributes['id'] = $id;

$attributes = $attributes->class('nav-item');
?>

<div {!! $attributes !!}>
    <a id="{{ $id }}__link" class="nav-link {{ $active ? 'active' : '' }}"
        href="{{ $href }}"
        @if ($target)
            data-bs-toggle="tab" data-bs-target="{{ $target }}"
        @endif
    >
        {!! $slot ?? '' !!}
    </a>
</div>

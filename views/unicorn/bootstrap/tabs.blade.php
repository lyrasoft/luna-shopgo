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

use Unicorn\Script\BootstrapScript;
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
    'variant',
    'keepactive'
);

$id ??= 'c-tab-' . uid();
$variant ??= 'tabs';
$keepactive ??= null;

if ($keepactive !== null) {
    if (!is_string($keepactive)) {
        $keepactive = '';
    }

    $keepactive = $keepactive ?: '#admin-form';

    $app->service(BootstrapScript::class)->keepTab($keepactive);
}

$attributes['id'] = $id;

?>

<div {!! $attributes !!}>
    <div id="{{ $id }}__buttons" class="nav nav-{{ $variant }}">
        {!! $slot ?? '' !!}
    </div>
</div>

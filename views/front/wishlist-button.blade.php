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

$added ??= false;

$attributes = $attributes->class('');
$attributes['href'] = 'javascript://';
$attributes['data-bs-toggle'] = 'tooltip';
$attributes['title'] = '待買清單';
$attributes['uni-wishlist-button'] = true;
$attributes['data-added'] = (int) $added;
$attributes['data-id'] = (int) $id;
$attributes['data-icon-active'] = 'fas fa-heart';
$attributes['data-icon-inactive'] = 'far fa-heart';

?>

<a {!! $attributes !!}>
    <i class="far fa-heart"></i>
</a>

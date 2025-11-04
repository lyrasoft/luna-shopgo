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

use Lyrasoft\ShopGo\Cart\CartStorage;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;
use Windwalker\Edge\Component\ComponentAttributes;

/**
 * @var $attributes ComponentAttributes
 * @var $link       mixed
 * @var $target     ?string
 */

$attributes = $attributes->class('c-cart-button d-inline-block');
$attributes['data-role'] = 'cart-button';

if ($link === true) {
    $attributes['href'] = (string) $nav->to('cart');
    $attributes['target'] = $target;
} elseif ($link !== false) {
    $attributes['href'] = $link;
    $attributes['target'] = $target;
}
?>
<a {!! $attributes !!}>
    <div class="c-cart-button__quantity">
        <i class="fa fa-cart-shopping"></i>

        <span class="badge bg-danger" data-role="cart-quantity">
            {{ $cartQuantity }}
        </span>
    </div>
</a>

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

use Unicorn\Field\LayoutField;
use Unicorn\Script\UnicornScript;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;
use Windwalker\Utilities\Arr;

/**
 * @var $field LayoutField
 */

$value = $field->getValue();

if (is_string($value)) {
    if (str_contains(',', $value)) {
        $value = Arr::explodeAndClear(',', $value);
    } else {
        $value = (array) $value;
    }
} else {
    if (is_json($value)) {
        $value = json_decode($value, true);
    } else {
        $value = (array) (string) $value;
    }
}

$asset->js('@shopgo/field/variants-flat-list.js');
?>

<div id="{{ $field->getId('-wrapper') }}"
    uni-variants-flat-list="@json($value)"
    data-product-selector="{{ $field->get('product_selector') }}"
>

</div>

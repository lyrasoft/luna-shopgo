<?php

declare(strict_types=1);

namespace App\view;

/**
 * Global variables
 * --------------------------------------------------------------
 * @var $app       AppContext      Application context.
 * @var $vm        ProductEditView  The view model object.
 * @var $uri       SystemUri       System Uri information.
 * @var $chronos   ChronosService  The chronos datetime service.
 * @var $nav       Navigator       Navigator object to build route.
 * @var $asset     AssetService    The Asset manage service.
 * @var $lang      LangService     The language translation service.
 */

use Lyrasoft\ShopGo\Entity\Discount;
use Lyrasoft\ShopGo\Entity\Product;
use Lyrasoft\ShopGo\Module\Admin\Product\ProductEditView;
use Lyrasoft\ShopGo\Script\ShopGoScript;
use Unicorn\Script\UnicornScript;
use Unicorn\Script\VueScript;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;

/**
 * @var $item      Product
 * @var $discounts Discount[]
 */

$app->service(ShopGoScript::class)->vueUtilities();

// $vueScript = $app->service(VueScript::class);
// $vueScript->vue();
// $vueScript->draggable();
// $vueScript->animate();

$uniScript = $app->service(UnicornScript::class);
$uniScript->translate('shopgo.discount.subtype.*');
$uniScript->translate('shopgo.discount.method.*');
$uniScript->translate('shopgo.discount.field.*');
$uniScript->translate('shopgo.product.discount.*');
$uniScript->translate('shopgo.product.text.*');
$uniScript->data('product.discounts.props', [
    'product' => $item,
    'discounts' => $discounts
]);
$uniScript->data('input.step', $vm->getMainInputStep());

?>
<product-discounts-edit-app id="product-variants-edit-app" data-novalidate></product-discounts-edit-app>

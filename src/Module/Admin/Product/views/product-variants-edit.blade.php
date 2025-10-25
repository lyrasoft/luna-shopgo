<?php

declare(strict_types=1);

namespace App\View;

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

use Lyrasoft\ShopGo\Entity\Product;
use Lyrasoft\ShopGo\Entity\ProductVariant;
use Lyrasoft\ShopGo\Module\Admin\Product\ProductEditView;
use Lyrasoft\ShopGo\ShopGoPackage;
use Unicorn\Image\ImagePlaceholder;
use Unicorn\Script\UnicornScript;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;

/**
 * @var $item     Product
 * @var $variants ProductVariant[]
 */

$defaultImage = $app->retrieve(ImagePlaceholder::class)->placeholderSquare();
$shopGo = $app->retrieve(ShopGoPackage::class);
$variantsLimit = (int) ($shopGo->config('product.variants_limit') ?? 100);

$uniScript = $app->service(UnicornScript::class);
$uniScript->translate('shopgo.product.variant.*');
$uniScript->translate('shopgo.product.button.*');
$uniScript->translate('shopgo.product.field.*');
$uniScript->translate('shopgo.product.message.*');
$uniScript->data('product.variants.props', [
    'product' => $item,
    'variants' => $variants,
]);
$uniScript->data('input.step', $vm->getMainInputStep());
$uniScript->data('defaultImage', $defaultImage);
$uniScript->data('variants.limit', $variantsLimit);

$uniScript->addRoute('@file_upload');
$uniScript->addRoute('@product_ajax');
?>

<product-variants-edit-app id="product-variants-edit-app" data-novalidate />



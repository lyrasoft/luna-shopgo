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

use Lyrasoft\ShopGo\Script\ShopGoScript;
use Unicorn\Script\UnicornScript;
use Unicorn\Script\VueScript;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;

// $app->service(ShopGoScript::class)->vueUtilities();

// $vueScript = $app->service(VueScript::class);
// $vueScript->vue();
// $vueScript->animate();

$uniScript = $app->service(UnicornScript::class);
$uniScript->data(
    'ap.attachments.props',
    [
        'attachmentData' => $attachmentsData
    ]
);
$uniScript->data('price.step', $vm->getMainCurrency()->getInputStep());

$uniScript->addRoute('product_modal', $nav->to('product_list')->layout('modal')->full());
$uniScript->addRoute('@additional_purchase_ajax');
$uniScript->translate('shopgo.additional.purchase.*');
$uniScript->translate('shopgo.discount.method.*');
$uniScript->translate('unicorn.field.title');
?>

<additional-purchase-attachments-app />

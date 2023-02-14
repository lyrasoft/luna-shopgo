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

$app->service(ShopGoScript::class)->vueUtilities();

$vueScript = $app->service(VueScript::class);
$vueScript->vue();
$vueScript->animate();

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
?>

<additional-purchase-attachments-app>
    <div class="l-ap-attachments" data-novalidate>

        <input name="attachments" type="hidden" value="__EMPTY_ARRAY__" />

        <div class="mb-3">
            <button v-if="attachmentSet.length > 0" type="button" class="btn btn-primary btn-sm"
                style="min-width: 100px"
                @click="openProductSelector"
            >
                <i class="fa fa-plus"></i>
                加入商品
            </button>
        </div>

        <transition-group v-if="attachmentSet.length > 0" name="fade">
            <attachment-product
                v-for="({ product, variants, open }, i) of attachmentSet" :key="product.id"
                :product="product"
                :variants="variants"
                :open="open"
                @remove="removeProduct(i)"
                class="mb-4"
                style="animation-duration: .3s"
            ></attachment-product>
        </transition-group>

        <div v-else class="card bg-light">
            <div class="card-body text-center py-5">
                <button type="button" class="btn btn-primary"
                    style="min-width: 100px"
                    @click="openProductSelector"
                >
                    <i class="fa fa-plus"></i>
                    加入商品
                </button>
            </div>
        </div>

        <uni-iframe-modal ref="productSelector"></uni-iframe-modal>
    </div>
</additional-purchase-attachments-app>

<x-components.attachment-product></x-components.attachment-product>

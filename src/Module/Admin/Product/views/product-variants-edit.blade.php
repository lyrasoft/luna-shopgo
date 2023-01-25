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

use App\Entity\Product;
use App\Entity\ProductVariant;
use App\Script\ShopGoScript;
use Unicorn\Script\UnicornScript;
use Unicorn\Script\VueScript;
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

$app->service(ShopGoScript::class)->vueUtilities();

$vueScript = $app->service(VueScript::class);
$vueScript->vue();
$vueScript->draggable();
$vueScript->animate();

$uniScript = $app->service(UnicornScript::class);
$uniScript->data('product.variants.props', [
    'variants' => $variants
]);

?>
<product-variants-edit-app id="product-variants-edit-app">
    <div class="row">
        <div class="col-lg-6 l-product-variant__list">
            <div class="card c-variant-list">
                <div class="card-header c-variant-list__toolbar d-flex">
                    <div class="ms-auto">
                        <button type="button" class="btn btn-sm btn-outline-danger"
                            v-if="countChecked() > 0"
                            @click="deleteVariants()"
                            :disabled="generate.edit">
                            <span class="fa fa-trash"></span>
                            @lang('shopgo.product.variant.button.delete.variants')
                        </button>

                        <button type="button" class="btn btn-sm btn-primary"
                            @click="generateCombinations()" :disabled="generate.edit">
                            <span class="fa fa-plus"></span>
                            @lang('shopgo.product.variant.button.add.variants')
                        </button>
                    </div>
                </div>

                <div class="c-variant-list__items list-group list-group-flush">
                    <div class="list-group-item c-variant-list__header d-flex"
                        style="margin-bottom: 0;">
                        <div class="me-2">
                            <input type="checkbox"
                                class="form-check-input"
                                @change="checkAll($event)"
                                :indeterminate.prop="countChecked() > 0 && countChecked() < items.length" />
                        </div>
                        <div class="me-2" style="width: 45px;">
                            @lang('shopgo.product.variant.label.cover')
                        </div>
                        <div class="me-2 flex-fill">
                            @lang('shopgo.product.variant.label.options')
                        </div>
                        <div class="me-2" style="width: 75px;">
                            @lang('shopgo.product.variant.label.stock.quantity')
                        </div>
                        <div class="" style="width: 66px;">
                            @lang('shopgo.product.variant.label.actions')
                        </div>
                    </div>
                    <div class="c-variant-list__scroll list-group list-group-flush"
                        style="overflow-y: scroll; height: 75vh; min-height: 400px">
                        <variant-list-item
                            v-for="(item, i) of items"
                            :key="item.uid"
                            :item="item"
                            :i="i"
                            :active="current?.id === item.id"
                            @edit="editVariant"
                            @oncheck="multiCheck"
                        ></variant-list-item>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 l-product-variant__manage">
            <variant-info-edit v-if="checkedItems.length"
                :variants="checkedItems"
            ></variant-info-edit>
        </div>
    </div>
</product-variants-edit-app>

<x-components.variant-list-item></x-components.variant-list-item>
<x-components.variant-info-edit></x-components.variant-info-edit>

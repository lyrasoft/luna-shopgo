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

?>

<script id="c-variant-info-edit" type="text-/x-template">
<div class="c-variant-edit card">
    <div class="card-header d-flex align-items-center">
        <div class="c-variant-edit__title">
            商品組合編輯
        </div>
        <div class="c-variant-edit__actions ms-auto">
            <button type="button" class="btn btn-primary btn-sm"
                @click="save">
                <span class="fa fa-save"></span>
                儲存
            </button>
            <button type="button" class="btn btn-outline-secondary btn-sm"
                @click="cancelEdit">
                <span class="fa fa-times"></span>
                取消
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="c-variant-edit__title mb-4">
            <span class="lead">@{{ items.length <= 1 ? current.title : '編輯多個項目' }}</span>
        </div>

{{--        <div class="d-flex mb-2 align-items-center" v-if="items.length <= 1">--}}
{{--            <label for="input-variant-default" class="mr-2">設為預設</label>--}}
{{--            <phoenix-switch name="default" v-model="current.default" size="sm"--}}
{{--                true-value="1"--}}
{{--                false-value="0"--}}
{{--                shape="circle"></phoenix-switch>--}}
{{--        </div>--}}

        <div class="d-flex gap-2">
            <div class="form-group mb-4" v-if="items.length <= 1">
                <label for="input-variant-model">型號</label>
                <input id="input-variant-model" type="text" class="form-control"
                    v-model="current.model" />
            </div>
            <div class="form-group mb-4" v-if="items.length <= 1">
                <label for="input-variant-sku">料號 (@{{ skuCount }})</label>
                <textarea id="input-variant-sku" type="text" class="form-control"
                    v-model="current.sku" rows="1"></textarea>
            </div>

            <div class="form-group mb-4">
                <label for="input-variant-price_offset">價格</label>
                <input id="input-variant-price_offset" type="number" class="form-control"
                    :value="current.price | number" @input="current.price_offset = $event.target.value" />
            </div>
            <div class="form-group mb-4">
                <label for="input-variant-weight_offset">重量</label>
                <input id="input-variant-weight_offset" type="number" class="form-control"
                    :value="current.weight_offset | number" @input="current.weight_offset = $event.target.value" />
            </div>
        </div>

        <div class="d-flex gap-2">
            <div class="form-group mb-4">
                <label for="input-variant-inventory">庫存</label>
                <input id="input-variant-inventory" type="number" class="form-control"
                    v-model="current.stockQuantity" min="0" />
            </div>
            <div class="form-group mb-4">
                <label for="input-variant-subtract" class="mr-2">減去庫存</label>
                <div class="form-check form-switch">
                    <input type="checkbox" id="input-variant-subtract"
                        class="form-check-input"
                        v-model="current.subtract"
                        :true-value="true"
                        :false-value="false"
                        role="switch"
                    />
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <div class="form-group mb-4">
                <label for="input-variant-publish_up">起始日期</label>
                <div class="input-group" vv-calendar>
                    <input id="input-variant-publish_up" type="text" class="form-control"
                        v-model="current.publishUp" />

                    <span class="input-group-addon input-group-append">
                        <span class="input-group-text">
                            <span class="fa fa-calendar"></span>
                        </span>
                    </span>
                </div>
            </div>
            <div class="form-group mb-4">
                <label for="input-variant-publish_down">結束日期</label>
                <div class="input-group" vv-calendar>
                    <input id="input-variant-publish_down" type="text" class="form-control"
                        v-model="current.publishDown" />

                    <span class="input-group-addon input-group-append">
                        <span class="input-group-text">
                            <span class="fa fa-calendar"></span>
                        </span>
                    </span>
                </div>
            </div>
        </div>

        <div class="variant-images mt-4" v-if="items.length <= 1">
{{--            <vue-drag-uploader--}}
{{--                :images="current.images"--}}
{{--                :url="getVariantImageUploadUrl(current)"--}}
{{--                :max-files="6"--}}
{{--                @change="current.images = $event"--}}
{{--                @uploading="loading.uploading = true"--}}
{{--                @uploaded="loading.uploading = false"--}}
{{--            >--}}
{{--            </vue-drag-uploader>--}}
        </div>

        <div class="mt-4">
            <button type="button" class="btn btn-primary btn-block"
                @click="saveVariant(current)" :disabled="currentSaving">
                <span class="fa fa-save"></span>
                @{{ currentSaving ? '儲存中' : '儲存' }}
            </button>
        </div>
    </div>
</div>
</script>

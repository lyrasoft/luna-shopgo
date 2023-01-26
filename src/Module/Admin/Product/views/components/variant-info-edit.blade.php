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
        <div class="c-variant-edit__title d-flex gap-2">
            <div>
                商品組合編輯
            </div>
            <div v-if="unsave">
                <span class="badge bg-warning">
                    Save Required
                </span>
            </div>
        </div>
        <div class="c-variant-edit__actions ms-auto">
            <button type="button" class="btn btn-primary btn-sm"
                @click="save"
                :disabled="!unsave"
            >
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
{{--            <div class="form-group mb-4" v-if="items.length <= 1">--}}
{{--                <label for="input-variant-model">型號</label>--}}
{{--                <input id="input-variant-model" type="text" class="form-control"--}}
{{--                    v-model="current.model" />--}}
{{--            </div>--}}
            <div class="form-group mb-4" v-if="items.length <= 1">
                <label for="input-variant-sku" class="form-label">料號</label>
                <textarea id="input-variant-sku" type="text" class="form-control"
                    v-model="current.sku" rows="1"></textarea>
            </div>

            <div class="form-group mb-4">
                <label for="input-variant-price_offset" class="form-label">價格</label>
                <input id="input-variant-price_offset" type="number" class="form-control"
                    v-model="current.price"
                    />
            </div>
        </div>

        <div class="d-flex gap-2">
            <div class="form-group mb-4">
                <label for="input-variant-length" class="form-label">Length</label>
                <input id="input-variant-length" type="number" class="form-control"
                    v-model="current.dimension.length" />
            </div>
            <div class="form-group mb-4">
                <label for="input-variant-width" class="form-label">Width</label>
                <input id="input-variant-width" type="number" class="form-control"
                    v-model="current.dimension.width" />
            </div>
            <div class="form-group mb-4">
                <label for="input-variant-height" class="form-label">Height</label>
                <input id="input-variant-height" type="number" class="form-control"
                    v-model="current.dimension.height" />
            </div>
            <div class="form-group mb-4">
                <label for="input-variant-weight" class="form-label">重量</label>
                <input id="input-variant-weight" type="number" class="form-control"
                    v-model="current.dimension.weight" />
            </div>
        </div>

        <div class="d-flex gap-2">
            <div class="form-group mb-4">
                <label for="input-variant-inventory" class="form-label">庫存</label>
                <input id="input-variant-inventory" type="number" class="form-control"
                    v-model="current.stockQuantity" min="0" />
            </div>
            <div class="form-group mb-4">
                <label for="input-variant-subtract" class="form-label">減去庫存</label>
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
                <label for="input-variant-publish_up" class="form-label">起始日期</label>
                <uni-flatpickr :options="flatpickrOptions">
                    <div class="input-group">
                        <input id="input-variant-publish_up" type="text" class="form-control"
                            data-input
                            v-model="current.publishUp" />
                        <button type="button"
                            class="btn btn-secondary"
                            data-toggle
                        >
                            <span class="fa fa-calendar"></span>
                        </button>
                        <button type="button"
                            class="btn btn-secondary"
                            data-clear
                        >
                            <span class="fa fa-times"></span>
                        </button>
                    </div>
                </uni-flatpickr>
            </div>
            <div class="form-group mb-4">
                <label for="input-variant-publish_down" class="form-label">結束日期</label>
                <uni-flatpickr :options="flatpickrOptions">
                    <div class="input-group">
                        <input id="input-variant-publish_down" type="text" class="form-control"
                            data-input
                            v-model="current.publishDown" />
                        <button type="button"
                            class="btn btn-secondary"
                            data-toggle
                        >
                            <span class="fa fa-calendar"></span>
                        </button>
                        <button type="button"
                            class="btn btn-secondary"
                            data-clear
                        >
                            <span class="fa fa-times"></span>
                        </button>
                    </div>
                </uni-flatpickr>
            </div>
        </div>

        <div class="variant-images mt-4" v-if="items.length <= 1">
            <vue-drag-uploader
                :model-value="JSON.parse(JSON.stringify(current.images))"
                @update:modelValue="current.images = JSON.parse(JSON.stringify($event))"
                :max-files="6"
                :url="getImageUploaderUrl()"
                accept="image/*"
                placeholder="Upload Images"
                @uploading="stack.push(true)"
                @uploaded="stack.pop()"
            >
            </vue-drag-uploader>
        </div>

        <div class="mt-4">
            <button type="button" class="btn btn-primary w-100"
                @click="saveVariant(current)">
                <span class="fa fa-save"></span>
                儲存
            </button>
        </div>
    </div>
</div>
</script>

<script>
    function variantInfoEdit() {
        const { ref, toRefs, reactive, computed, watch } = Vue;

        return {
            name: 'VariantInfoEdit',
            template: u.selectOne('#c-variant-info-edit').innerHTML,
            components: {
                VueDragUploader: VueDragUploader
            },
            props: {
                variants: Array,
            },
            setup(props, { emit }) {
                const state = reactive({
                    current: {},
                    items: [],
                    originCopy: '',
                    flatpickrOptions: JSON.stringify(
                        {
                            dateFormat: 'Y-m-d H:i:S',
                            enableTime: true,
                            enableSeconds: true,
                            allowInput: true,
                            time_24hr: true,
                            // wrap: true,
                            monthSelect: false,
                        }
                    ),
                    stack: u.stack('uploading')
                });

                watch(() => props.variants, () => {
                    state.current = {
                        sku: '',
                        price: '',
                        stockQuantity: '',
                        publishUp: '',
                        publishDown: '',
                        images: [],
                        dimension: {
                            width: '',
                            height: '',
                            length: '',
                            weight: '',
                            unitWeight: '',
                        }
                    };
                    state.items = props.variants;

                    if (state.items.length === 1) {
                        state.current = JSON.parse(JSON.stringify(state.items[0]));
                    }

                    state.originCopy = JSON.stringify(state.current);
                }, { immediate: true });

                const isMultiple = computed(() => state.items.length > 1);
                const unsave = computed(() => state.originCopy !== JSON.stringify(state.current));

                watch(() => unsave, () => {
                    emit('unsavechange', unsave.value);
                });

                // window.addEventListener('beforeunload', (e) => {
                //     if (unsave.value) {
                //         e.preventDefault();
                //         e.stopPropagation();
                //         e.returnValue = 'Save Required';
                //     }
                // });

                function save() {
                    if (!unsave.value) {
                        return;
                    }

                    if (!isMultiple.value) {
                        state.items[0] = Object.assign(state.items[0], state.current);
                        state.items[0].unsave = unsave.value;
                    } else {
                        for (const item of state.items) {
                            ShopgoVueUtilities.mergeRecursive(
                                item,
                                state.current,
                            );

                            item.unsave = unsave.value;
                        }
                    }

                    state.originCopy = JSON.stringify(state.current);
                }

                function cancelEdit() {
                    emit('cancel');
                }

                function getImageUploaderUrl() {
                    return u.route('file_upload', { profile: 'image' });
                }

                function imagesChange(images) {
                    console.log(images);
                }

                return {
                    ...toRefs(state),
                    unsave,

                    save,
                    cancelEdit,
                    getImageUploaderUrl,
                    imagesChange,
                }
            }
        }
    }
</script>

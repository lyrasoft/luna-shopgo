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
                @lang('shopgo.product.variant.edit.title')
            </div>
        </div>
        <div class="c-variant-edit__actions ms-auto">
            <button type="button" class="btn btn-outline-secondary btn-sm"
                @click="cancelEdit">
                <span class="fa fa-times"></span>
                @lang('shopgo.product.button.cancel')
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="c-variant-edit__title mb-4">
            <span
                class="lead">@{{ items.length <= 1 ? current.title : '@lang('shopgo.product.variant.edit.multiple')' }}</span>
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
                <label for="input-variant-sku" class="form-label">
                    @lang('shopgo.product.field.sku')
                </label>
                <textarea id="input-variant-sku" type="text" class="form-control"
                    v-model="current.sku" rows="1"></textarea>
            </div>

            <div class="form-group mb-4">
                <label for="input-variant-price" class="form-label">
                    @lang('shopgo.product.field.price')
                </label>
                <input id="input-variant-price" type="number" class="form-control"
                    v-model="current.price"
                    min="0"
                    :step="inputStep"
                />
            </div>
        </div>

        <div class="d-flex gap-2">
            <div class="form-group mb-4">
                <label for="input-variant-length" class="form-label">
                    @lang('shopgo.product.field.length')
                </label>
                <input id="input-variant-length" type="number" class="form-control"
                    v-model="current.dimension.length"
                    min="0"
                />
            </div>
            <div class="form-group mb-4">
                <label for="input-variant-width" class="form-label">
                    @lang('shopgo.product.field.width')
                </label>
                <input id="input-variant-width" type="number" class="form-control"
                    v-model="current.dimension.width"
                    min="0"
                />
            </div>
            <div class="form-group mb-4">
                <label for="input-variant-height" class="form-label">
                    @lang('shopgo.product.field.height')
                </label>
                <input id="input-variant-height" type="number" class="form-control"
                    v-model="current.dimension.height"
                    min="0"
                />
            </div>
            <div class="form-group mb-4">
                <label for="input-variant-weight" class="form-label">
                    @lang('shopgo.product.field.weight')
                </label>
                <input id="input-variant-weight" type="number" class="form-control"
                    v-model="current.dimension.weight"
                    min="0"
                />
            </div>
        </div>

        <div class="d-flex gap-2">
            <div class="form-group mb-4">
                <label for="input-variant-inventory" class="form-label">
                    @lang('shopgo.product.field.stock.quantity')
                </label>
                <input id="input-variant-inventory" type="number" class="form-control"
                    v-model="current.stockQuantity" min="0" />
            </div>
            <div class="form-group mb-4">
                <label for="input-variant-subtract" class="form-label">
                    @lang('shopgo.product.field.subtract')
                </label>
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
            currentHash: '',
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
            stack: u.stack('uploading'),
            inputStep: u.data('input.step') || '0.0001',
          });

          watch(() => props.variants, () => {
            let item = {
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
              item = state.items[0];
            }

            state.currentHash = hashItem(item);

            state.current = item;
          }, { immediate: true });

          function hashItem(item) {
            const newItem = { ...item };

            delete newItem.checked;
            delete newItem.unsave;

            return u.md5(JSON.stringify(newItem));
          }

          const isMultiple = computed(() => state.items.length > 1);
          // const unsave = computed(() => state.originCopy !== JSON.stringify(state.current));

          watch(() => state.current, () => {
            if (state.currentHash !== '' && state.currentHash !== hashItem(state.current)) {
              updateUnsaves();
            }
          }, { deep: true });

          watch(() => state.current.price, (v) => {
            if (v < 0) {
              state.current.price = 0;
            }
          });

          function updateUnsaves() {
            if (!isMultiple.value) {
              state.current.cover = state.current.images[0]?.url || '';
              state.items[0].unsave = true;
            } else {
              for (const item of state.items) {
                ShopgoVueUtilities.mergeRecursive(
                  item,
                  state.current,
                );

                item.unsave = true;
              }
            }
          }

          function cancelEdit() {
            emit('cancel');
          }

          function getImageUploaderUrl() {
            return u.route('file_upload', { profile: 'image' });
          }

          return {
            ...toRefs(state),

            cancelEdit,
            getImageUploaderUrl,
          };
        }
      };
    }
</script>

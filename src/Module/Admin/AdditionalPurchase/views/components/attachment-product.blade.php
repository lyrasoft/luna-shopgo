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

use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;

?>

<script id="c-attachment-product" type="x-template">
<div class="card c-attachment">
    <div class="c-attachment__product card-header border-bottom d-flex gap-3">
        <div>
            <div class="ratio ratio-1x1" style="width: 55px">
                <img class="object-fit-cover" :src="product.variant.cover" alt="cover">
            </div>
        </div>
        <div class="w-100">
            <div class="d-flex align-items-center gap-2 mb-2">
                <h4 class="m-0">@{{ product.title }}</h4>
                <div>
                </div>

                <div class="ms-auto">
                    <div>
                        <span class="badge border border-secondary text-secondary">
                            #@{{ product.id }}
                        </span>
                        <span class="badge bg-secondary">
                            @{{ $lang('shopgo.additional.purchase.text.selected.count', checks) }}
                        </span>
                        <button type="button" class="btn btn-outline-secondary btn-sm"
                            @click="$emit('remove')">
                            <i class="fa fa-trash"></i>
                            @lang('shopgo.additional.purchase.button.delete')
                        </button>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-3">
                <div class="form-check">
                    <input :id="`input-sync-all-${product.id}`" type="checkbox" class="form-check-input"
                        v-model="syncAll"
                    />
                    <label :for="`input-sync-all-${product.id}`">
                        <i class="fa" :class="[ syncAll ? 'fa-lock' : 'fa-unlock' ]"></i>
                        @lang('shopgo.additional.purchase.text.sync.all')
                    </label>
                </div>

                <div class="ms-auto">
                    <a href="javascript://" class="px-2 py-2"
                        @click="open = !open">
                        <i class="fa" :class="[ open ? 'fa-chevron-down' : 'fa-chevron-up' ]"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="c-attachment__variants" ref="variantList"
        style="overflow: hidden; display: none;">
        <table class="table">
            <thead>
            <tr>
                <th style="width: 1%;">
                    <input type="checkbox"
                        class="form-check-input"
                        :checked="checks === items.length"
                        :indeterminate.prop="checks !== 0 && checks < items.length"
                        @click="toggleAll($event.target)"
                    />
                </th>
                <th>
                    @lang('unicorn.field.title')
                </th>
                <th class="text-nowrap" style="width: 23%;">
                    @lang('shopgo.additional.purchase.field.method')
                </th>
                <th class="text-nowrap" style="width: 15%;">
                    @lang('shopgo.additional.purchase.field.pricing')
                </th>
                <th class="text-nowrap" style="width: 10%;">
                    @lang('shopgo.additional.purchase.field.max.quantity')
                </th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="item of items" :key="item.id"
                class="">
                <td>
                    <input type="checkbox"
                        :id="`input-variant-${item.id}`"
                        class="form-check-input"
                        v-model="item.attachment.state"
                        :true-value="1"
                        :false-value="0"
                    />
                </td>
                <td>
                    <label :for="`input-variant-${item.id}`">
                        @{{ item.title }}
                    </label>

                    <div class="d-none">
                        <input :name="`attachments[${product.id}][${item.id}][id]`"
                            :value="item.attachment?.id"
                            type="hidden" />
                        <input :name="`attachments[${product.id}][${item.id}][method]`"
                            :value="item.attachment.method"
                            type="hidden" />
                        <input :name="`attachments[${product.id}][${item.id}][price]`"
                            :value="item.attachment.price"
                            type="hidden" />
                        <input :name="`attachments[${product.id}][${item.id}][max_quantity]`"
                            :value="item.attachment.maxQuantity"
                            type="hidden" />
                        <input :name="`attachments[${product.id}][${item.id}][state]`"
                            :value="item.attachment.state"
                            type="hidden" />
                    </div>
                </td>
                <td>
                    <select class="form-select form-select-sm"
                        v-model="item.attachment.method"
                        @change="onMethodChange(item)"
                    >
                        <option value="percentage">
                            @lang('shopgo.discount.method.percentage')
                        </option>
                        <option value="offsets">
                            @lang('shopgo.discount.method.offsets')
                        </option>
                        <option value="fixed">
                            @lang('shopgo.discount.method.fixed')
                        </option>
                    </select>
                </td>
                <td>
                    <div class="input-group input-group-sm flex-nowrap">
                        <input type="number"
                            class="form-control form-control-sm"
                            :step="getPriceStep(item)"
                            v-model.number="item.attachment.price"
                            @change="normalizePricing(item)"
                            style="min-width: 80px"
                        />
                            <span v-if="item.attachment.method === 'percentage'" class="input-group-text">
                                %
                            </span>
                    </div>
                </td>
                <td>
                    <input type="number"
                        class="form-control form-control-sm"
                        v-model="item.attachment.maxQuantity"
                        @change="onMaxQuantityChange(item)"
                        min="0"
                        max="30"
                    />
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
</script>

<script>
    function attachmentProduct() {
        const { ref, onMounted, computed, createApp, toRefs, reactive, watch } = Vue;

        return {
            template: '#c-attachment-product',
            props: {
                product: Object,
                variants: Array,
                open: Boolean,
            },
            setup(props) {
                const state = reactive({
                    items: ShopgoVueUtilities.prepareVueItemList(
                        props.variants,
                        (item) => {
                            item.attachment = item.attachment || {
                                method: 'offsets',
                                price: 0,
                                maxQuantity: '',
                                state: 1,
                            };
                        }
                    ),
                    open: props.open,
                    syncAll: false
                });

                function toggleAll(el) {
                    for (const item of state.items) {
                        item.attachment.state = el.checked ? 1 : 0;
                    }
                }

                const checks = computed(() => {
                    return state.items.filter((item) => Number(item.attachment.state) === 1).length;
                });

                function onMethodChange(item) {
                    syncAllFields(item.attachment.method, 'method');

                    normalizePricing(item);
                }

                function onMaxQuantityChange(item) {
                    let qty = item.attachment.maxQuantity;

                    qty = Math.max(qty, 0);
                    qty = Math.min(qty, 30);

                    item.attachment.maxQuantity = qty;

                    syncAllFields(item.attachment.maxQuantity, 'maxQuantity');
                }

                function normalizePricing(item) {
                    if (
                        item.attachment.method === 'percentage'
                        && (item.attachment.price < 0 || item.attachment.price > 100)
                    ) {
                        item.attachment.price = Math.min(
                            Math.abs(item.attachment.price),
                            100
                        );
                    }

                    if (
                        item.attachment.method === 'offsets'
                        && item.attachment.price > 0
                    ) {
                        item.attachment.price = -item.attachment.price;
                    }

                    if (
                        item.attachment.method === 'fixed'
                        && item.attachment.price < 0
                    ) {
                        item.attachment.price = -item.attachment.price;
                    }

                    syncAllFields(item.attachment.price, 'price');
                }

                function syncAllFields(value, field) {
                    if (!state.syncAll) {
                        return;
                    }

                    for (const item of state.items) {
                        item.attachment[field] = value;
                    }
                }

                function getPriceStep(item) {
                    if (item.attachment.method === 'percentage') {
                        return '1';
                    }

                    return u.data('price.step') || '0.0001';
                }

                // Open / Close
                const variantList = ref(null);

                watch(() => state.open, (v) => {
                    setTimeout(() => {
                        if (v) {
                            u.$ui.slideDown(variantList.value);
                        } else {
                            u.$ui.slideUp(variantList.value);
                        }
                    }, 0);
                }, { immediate: true });

                watch(() => props.open, () => {
                   state.open = props.open;
                });

                return {
                    ...toRefs(state),
                    checks,
                    variantList,

                    toggleAll,
                    onMethodChange,
                    onMaxQuantityChange,
                    normalizePricing,
                    syncAllFields,
                    getPriceStep,
                };
            }
        };
    }
</script>

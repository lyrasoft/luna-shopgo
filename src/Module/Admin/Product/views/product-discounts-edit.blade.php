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

$vueScript = $app->service(VueScript::class);
$vueScript->vue();
$vueScript->draggable();
$vueScript->animate();

$uniScript = $app->service(UnicornScript::class);
$uniScript->translate('shopgo.discount.subtype.*');
$uniScript->translate('shopgo.discount.method.*');
$uniScript->data('product.discounts.props', [
    'product' => $item,
    'discounts' => $discounts
]);
$uniScript->data('input.step', $vm->getMainInputStep());

?>
<product-discounts-edit-app id="product-variants-edit-app" data-novalidate>
    <div class="l-product-discount row">
        <div class="col-lg-6 l-product-discount__list">
            <div class="card c-discount-list">
                <div class="card-header c-discount-list__toolbar d-flex">
                    <div class="ms-auto">
                        <button type="button" class="btn btn-sm btn-outline-danger"
                            v-if="countChecked() > 0"
                            @click="deleteItems()">
                            <span class="fa fa-trash"></span>
                            @lang('shopgo.product.button.delete')
                        </button>

                        <button type="button" class="btn btn-sm btn-primary"
                            @click="newItem()">
                            <span class="fa fa-plus"></span>
                            @lang('shopgo.product.discount.button.new')
                        </button>
                    </div>
                </div>

                <div class="c-discount-list__items list-group list-group-flush">
                    <div class="list-group-item c-discount-list__header d-flex gap-2" style="margin-bottom: 0;">
                        <div class="">
                            <span class="fa fa-arrows-alt-v fa-fw me-1"></span>
                            <input type="checkbox" @change="checkAll($event)"
                                class="form-check-input"
                                :indeterminate.prop="countChecked() > 0 && countChecked() < items.length" />
                        </div>
                        <div class="flex-fill" style="">
                            @lang('shopgo.product.discount.field.type')
                        </div>
                        <div class="text-end" style="width: 100px;">
                            @lang('shopgo.discount.field.min.product.quantity')
                        </div>
                        <div class="text-end" style="width: 100px;">
                            @lang('shopgo.product.discount.field.price.offsets')
                        </div>
                        <div class="" style="width: 75px;">
                            @lang('shopgo.product.discount.field.time')
                        </div>
                        <div class="" style="width: 75px;">
                            @lang('shopgo.product.discount.actions')
                        </div>
                    </div>

                    {{-- List --}}
                    <div class="c-discount-list__scroll list-group list-group-flush"
                        style="overflow-y: scroll; height: 75vh; min-height: 400px">
                        <draggable v-model="items" @sort="reorder"
                            :animation="300"
                            handle=".handle"
                            item-key="uid"
                        >
                            {{-- Discount Item--}}
                            <template #item="{ element: item, index: i }">
                                <div class="list-group-item c-discount-item"
                                    :class="{ 'text-bg-dark': current.uid === item.uid }"
                                    :key="item.uid"
                                    :data-id="item.id"
                                >
                                    <div class="list-group-item__wrapper d-flex align-items-center gap-2">
                                        {{-- Checkbox --}}
                                        <div class="c-discount-item__control d-flex flex-nowrap">
                                            <span class="fa fa-fw fa-ellipsis-v handle" style="cursor: move;"></span>
                                            <input type="checkbox" v-model="item.checked"
                                                class="form-check-input"
                                                @click="multiCheck($event, item, i)" />
                                        </div>

                                        {{-- Type --}}
                                        <div class="c-discount-item__type flex-fill text-nowrap">
                                            @{{ $lang('shopgo.discount.subtype.' + item.subtype) }}
                                            <div v-if="item.unsave">
                                                <span class="badge bg-warning">
                                                    @lang('shopgo.product.text.save.required')
                                                </span>
                                            </div>
                                        </div>

                                        {{-- Start Qty --}}
                                        <div class="c-discount-item__quantity text-end"
                                            style="width: 100px;">
                                            @{{ item.subtype === 'discount' ? item.minProductQuantity : '-' }}
                                        </div>

                                        {{-- Pricing --}}
                                        <div class="c-discount-item__price text-end flex-fill"
                                            style="width: 100px">
                                            @{{ $priceOffset(item.price, item.method) }}
                                        </div>

                                        {{-- Time --}}
                                        <div class="c-discount-item__time-limit text-center"
                                            style="width: 75px;">
                                            <span v-if="item.publishUp || item.publishDown"
                                                class="fa fa-clock has-tooltip"
                                                v-tooltip
                                                :title="timeLimit(item)"
                                            ></span>
                                            <span v-else>-</span>
                                        </div>

                                        {{-- Actions --}}
                                        <div class="c-discount-item__actions text-nowrap text-end"
                                            style="width: 75px">
                                            <button type="button" class="btn btn-sm btn-light border-secondary"
                                                @click="editItem(item, i)">
                                                <span class="fa fa-pencil-alt"></span>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-light border-secondary"
                                                @click="deleteItems(item)">
                                                <span class="fa fa-trash text-danger"></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </draggable>
                    </div>
                </div>
            </div>
        </div>

        {{-- Edit --}}
        <div class="col-lg-6 l-product-discount__manage">
            <div v-if="current.uid" class="c-discount-edit card">
                <div class="card-header d-flex">
                    <div class="c-discount-edit__title">
                        @lang('shopgo.product.discount.edit.title')
                    </div>
                    <div class="c-discount-edit__actions ms-auto">

                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-2">
                        {{-- Mode --}}
                        <div class="form-group mb-4">
                            <label for="input-discount-subtype" class="form-label">
                                @lang('shopgo.product.discount.field.mode')
                            </label>
                            <select id="input-discount-subtype" class="form-select"
                                style="min-width: 100px;"
                                v-model="current.subtype">
                                <option value="discount">
                                    @lang('shopgo.discount.subtype.discount')
                                </option>
                                <option value="special">
                                    @lang('shopgo.discount.subtype.special')
                                </option>
                            </select>
                        </div>

                        {{-- Start Qty --}}
                        <transition name="fade">
                            <div class="form-group mb-4" v-if="current.subtype === 'discount'"
                                style="animation-duration: .3s">
                                <label for="input-discount-quantity" class="form-label">
                                    @lang('shopgo.discount.field.min.product.quantity')
                                </label>
                                <input id="input-discount-quantity" type="number" class="form-control"
                                    v-model="current.minProductQuantity" min="0" />
                            </div>
                        </transition>
                    </div>

                    <div class="d-flex gap-2">
                        {{-- Publish Up --}}
                        <div class="form-group mb-4">
                            <label for="input-discount-start_date" class="form-label">
                                @lang('shopgo.discount.field.publish.up')
                            </label>
                            <uni-flatpickr :options="flatpickrOptions">
                                <div class="input-group" data-calendar>
                                    <input id="input-discount-start_date" type="text" class="form-control"
                                        v-model="current.publishUp"
                                        data-input
                                    />

                                    <button type="button"
                                        class="btn btn-secondary"
                                        data-toggle
                                    >
                                        <span class="fa fa-calendar"></span>
                                    </button>
                                    <button type="button"
                                        class="btn btn-secondary"
                                        data-clear
                                        @click="current.publishUp = ''"
                                    >
                                        <span class="fa fa-times"></span>
                                    </button>
                                </div>
                            </uni-flatpickr>
                        </div>

                        {{-- Publish Down --}}
                        <div class="form-group mb-4">
                            <label for="input-discount-end_date" class="form-label">
                                @lang('shopgo.discount.field.publish.down')
                            </label>
                            <uni-flatpickr :options="flatpickrOptions">
                                <div class="input-group" data-calendar>
                                    <input id="input-discount-end_date" type="text" class="form-control"
                                        v-model="current.publishDown"
                                        data-input
                                    />

                                    <button type="button"
                                        class="btn btn-secondary"
                                        data-toggle
                                    >
                                        <span class="fa fa-calendar"></span>
                                    </button>
                                    <button type="button"
                                        class="btn btn-secondary"
                                        data-clear
                                        @click="current.publishDown = ''"
                                    >
                                        <span class="fa fa-times"></span>
                                    </button>
                                </div>
                            </uni-flatpickr>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        {{-- Pricing --}}
                        <div class="form-group mb-4">
                            <label for="input-discount-price" class="form-label">
                                @lang('shopgo.product.discount.field.price.offsets')
                            </label>
                            <div class="input-group">
                                <input id="input-discount-price" type="number" class="form-control"
                                    :value="current.price"
                                    @input="current.price = $event.target.value"
                                    @change="correctPriceInput"
                                    :step="current.method === 'percentage' ? 0.1 : inputStep"
                                />
                                <span v-if="current.method === 'percentage'" class="input-group-text">
                                    %
                                </span>
                            </div>
                        </div>

                        {{-- Pricing Method --}}
                        <div class="form-group mb-4">
                            <label for="input-discount-method" class="form-label">
                                @lang('shopgo.discount.field.method')
                            </label>
                            <select id="input-discount-method" class="form-select"
                                v-model="current.method">
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
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <textarea name="discounts" class="d-none" :value="itemsJSON"></textarea>
    </div>
</product-discounts-edit-app>

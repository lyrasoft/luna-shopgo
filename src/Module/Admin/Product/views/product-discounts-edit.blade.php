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

use App\Entity\Discount;
use App\Entity\Product;
use App\Script\ShopGoScript;
use Unicorn\Script\BootstrapScript;
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
                            刪除
                        </button>

                        <button type="button" class="btn btn-sm btn-primary"
                            @click="newItem()">
                            <span class="fa fa-plus"></span>
                            新增折扣/特價
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
                            類型
                        </div>
                        <div class="text-end" style="width: 100px;">
                            起始數量
                        </div>
                        <div class="text-end" style="width: 100px;">
                            價格調整
                        </div>
                        <div class="" style="width: 75px;">
                            時間限制
                        </div>
                        <div class="" style="width: 75px;">
                            操作
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
                            <template #item="{ element: item, index: i }">
                                <div class="list-group-item c-discount-item"
                                    :class="{active: current.uid === item.uid}" :key="item.uid"
                                    :data-id="item.id"
                                >
                                    <div class="list-group-item__wrapper d-flex align-items-center gap-2">
                                        <div class="c-discount-item__control d-flex flex-nowrap">
                                            <span class="fa fa-fw fa-ellipsis-v handle" style="cursor: move;"></span>
                                            <input type="checkbox" v-model="item.checked"
                                                class="form-check-input"
                                                @click="multiCheck($event, item, i)" />
                                        </div>
                                        <div class="c-discount-item__type flex-fill text-nowrap">
                                            @{{ $lang('shopgo.discount.subtype.' + item.subtype) }}
                                            <div v-if="item.unsave">
                                                <span class="badge bg-warning">
                                                    Unsave
                                                </span>
                                            </div>
                                        </div>
                                        <div class="c-discount-item__quantity text-end"
                                            style="width: 100px;">
                                            @{{ item.subtype === 'discount' ? item.minProductQuantity : '-' }}
                                        </div>
                                        <div class="c-discount-item__price text-end flex-fill"
                                            style="width: 100px">
                                            @{{ $priceOffset(item.price, '$') }}
                                        </div>
                                        <div class="c-discount-item__time-limit text-center"
                                            style="width: 75px;">
                                            <span v-if="item.publishUp || item.publishDown"
                                                class="fa fa-clock has-tooltip"
                                                v-tooltip
                                                :title="timeLimit(item)"
                                            ></span>
                                            <span v-else>-</span>
                                        </div>
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
                        折扣/特價 編輯
                    </div>
                    <div class="c-discount-edit__actions ms-auto">
                        <button type="button" class="btn btn-primary btn-sm"
                            style="width: 150px;"
                            @click="saveItem(current)" :disabled="!currentEditUnsave">
                            <span class="fa fa-save"></span>
                            儲存
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm"
                            @click="cancelEdit()">
                            <span class="fa fa-times"></span>
                            取消
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-2">
                        <div class="form-group mb-4">
                            <label for="input-discount-subtype" class="form-label">模式</label>
                            <select id="input-discount-subtype" class="form-select"
                                style="min-width: 100px;"
                                v-model="current.subtype">
                                <option value="discount">折扣</option>
                                <option value="special">特價</option>
                            </select>
                        </div>

                        <transition name="fade">
                            <div class="form-group mb-4" v-if="current.subtype === 'discount'"
                                style="animation-duration: .3s">
                                <label for="input-discount-quantity" class="form-label">起始數量</label>
                                <input id="input-discount-quantity" type="number" class="form-control"
                                    v-model="current.minProductQuantity" min="0" />
                            </div>
                        </transition>
                    </div>

                    <div class="d-flex gap-2">
                        <div class="form-group mb-4">
                            <label for="input-discount-start_date" class="form-label">起始日期</label>
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
                                    >
                                        <span class="fa fa-times"></span>
                                    </button>
                                </div>
                            </uni-flatpickr>
                        </div>
                        <div class="form-group mb-4">
                            <label for="input-discount-end_date" class="form-label">結束日期</label>
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
                                    >
                                        <span class="fa fa-times"></span>
                                    </button>
                                </div>
                            </uni-flatpickr>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <div class="form-group mb-4">
                            <label for="input-discount-price" class="form-label">價格調整</label>
                            <div class="input-group">
                                <input id="input-discount-price" type="number" class="form-control"
                                    :value="current.price" @input="current.price = $event.target.value" />
                                <span v-if="current.method === 'percentage'" class="input-group-text">
                                    %
                                </span>
                            </div>
                        </div>
                        <div class="form-group mb-4">
                            <label for="input-discount-method" class="form-label">調整模式</label>
                            <select id="input-discount-method" class="form-select"
                                v-model="current.method">
                                <option value="percentage">百分比</option>
                                <option value="offsets">金額調整</option>
                                <option value="fixed">指定金額</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="button" class="btn btn-primary w-100"
                            @click="saveItem(current)" :disabled="!currentEditUnsave">
                            <span class="fa fa-save"></span>
                            儲存
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <textarea name="discounts" class="d-none" :value="itemsJSON"></textarea>
    </div>
</product-discounts-edit-app>

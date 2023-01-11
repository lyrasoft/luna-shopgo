<?php

declare(strict_types=1);

namespace App\View;

/**
 * Global variables
 * --------------------------------------------------------------
 * @var  $app       AppContext      Application context.
 * @var  $vm        ProductFeatureEditView  The view model object.
 * @var  $uri       SystemUri       System Uri information.
 * @var  $chronos   ChronosService  The chronos datetime service.
 * @var  $nav       Navigator       Navigator object to build route.
 * @var  $asset     AssetService    The Asset manage service.
 * @var  $lang      LangService     The language translation service.
 */

use App\Entity\ProductFeature;
use App\Module\Admin\ProductFeature\ProductFeatureEditView;
use App\Script\ShopGoScript;
use Unicorn\Script\UnicornScript;
use Unicorn\Script\VueScript;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;
use Windwalker\Form\Form;

/**
 * @var Form           $form
 * @var ProductFeature $item
 */

$app->service(ShopGoScript::class)->utilities();

$vueScript = $app->service(VueScript::class);
$vueScript->vue();
$vueScript->draggable();
$vueScript->animate();

$uniScript = $app->service(UnicornScript::class);
$uniScript->data('options', $item?->getOptions() ?? []);
?>

@extends('admin.global.body-edit')

@section('toolbar-buttons')
    @include('edit-toolbar')
@stop

@section('content')
    <form name="admin-form" id="admin-form"
        uni-form-validate='{"scroll": true}'
        action="{{ $nav->to('product_feature_edit') }}"
        method="POST" enctype="multipart/form-data">

        <x-title-bar :form="$form"></x-title-bar>

        <div class="row">
            <div class="col-lg-9">
                <div id="product-feature-app">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card c-feature-option-list">
                                <div class="card-header d-flex align-items-center">
                                    <h3 class="m-0">選項</h3>
                                    <div class="c-list-top-toolbar ms-auto">
                                        <button type="button" class="btn btn-sm btn-primary"
                                            @click="addNewItem()">
                                            <span class="fa fa-plus"></span>
                                            新增
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                            @click="removeItems()" :disabled="selected.length === 0">
                                            <span class="fa fa-trash"></span>
                                            刪除
                                        </button>
                                    </div>
                                </div>

                                <div class="c-option-list list-group list-group-flush">
                                    <draggable v-model="items" handle=".handle" item-key="uid">
                                        <template #item="{ element: item, index }">
                                            <div class="list-group-item c-option-item"
                                                :class="[{active: current === item}]"
                                                @click="selectItem(item)">
                                                <div class="d-flex align-items-center">
                                                    <div class="c-option-item__control mr-3">
                                                        <span class="fa fa-fw fa-ellipsis-v handle"></span>
                                                        <input type="checkbox" name="selected[]" v-model="selected"
                                                            :value="item.uid"
                                                            @click.stop="" />
                                                    </div>
                                                    <div v-if="type === 'color'" class="c-option-item__color mr-3">
                                                        <div class="c-option-item__color-box rounded"
                                                            style="width: 25px; height: 25px;"
                                                            :style="{'background-color': item.data.color || '#eee'}"></div>
                                                    </div>
                                                    <div class="c-option-control__title flex-grow-1">
                                                        <div class="h5 m-0">
                                                            @{{ item.title || '-未命名-' }}
                                                        </div>
                                                    </div>
                                                    <div class="c-option-control__actions d-flex align-items-center">
                                                        {{--<div class="mr-2" @click.stop="">--}}
                                                        {{--<label :for="'default-radio-' + item.uid">預設</label>--}}
                                                        {{--<input type="radio" name="item[default]" :value="item.uid"--}}
                                                        {{--:id="'default-radio-' + item.uid"--}}
                                                        {{--@click="setDefault(i)" />--}}
                                                        {{--</div>--}}
                                                        <button type="button"
                                                            class="btn btn-sm btn-light border-secondary mr-1"
                                                            @click.stop="addNewItem(item)">
                                                            <span class="fa fa-plus"></span>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                            @click.stop="removeItem(item)">
                                                            <span class="fa fa-trash"></span>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="d-none">
                                                    <input type="hidden" :name="`options[${item.uid}][id]`"
                                                        :value="item.id" />
                                                    <input type="hidden" :name="`options[${item.uid}][title]`"
                                                        :value="item.title" />
                                                    <input type="hidden" :name="`options[${item.uid}][value]`"
                                                        :value="item.value" />
                                                    <input type="hidden" :name="`options[${item.uid}][data]`"
                                                        :value="toJson(item.data)" />
                                                    <input type="hidden" :name="`options[${item.uid}][state]`"
                                                        value="1" />
                                                </div>
                                            </div>
                                        </template>
                                    </draggable>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <x-fieldset name="meta" :title="$lang('unicorn.fieldset.meta')"
                    :form="$form"
                    class="mb-4"
                    is="card"
                >
                </x-fieldset>
            </div>
        </div>

        <div class="d-none">
            @if ($idField = $form?->getField('id'))
                <input name="{{ $idField->getInputName() }}" type="hidden" value="{{ $idField->getValue() }}" />
            @endif

            <x-csrf></x-csrf>
        </div>
    </form>
@stop

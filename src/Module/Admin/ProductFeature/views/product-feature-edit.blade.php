<?php

declare(strict_types=1);

namespace App\View;

/**
 * Global variables
 * --------------------------------------------------------------
 * @var  $app       AppContext      Application context.
 * @var  $vm        \Lyrasoft\ShopGo\Module\Admin\ProductFeature\ProductFeatureEditView  The view model object.
 * @var  $uri       SystemUri       System Uri information.
 * @var  $chronos   ChronosService  The chronos datetime service.
 * @var  $nav       Navigator       Navigator object to build route.
 * @var  $asset     AssetService    The Asset manage service.
 * @var  $lang      LangService     The language translation service.
 */

use Lyrasoft\ShopGo\Entity\ProductFeature;
use Lyrasoft\ShopGo\Module\Admin\ProductFeature\ProductFeatureEditView;
use Lyrasoft\ShopGo\Script\ShopGoScript;
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

$app->service(ShopGoScript::class)->vueUtilities();

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
                <input type="hidden" name="options" value="__EMPTY_ARRAY__" />
                <product-feature-app id="product-feature-app">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card c-feature-option-list">
                                <div class="card-header d-flex align-items-center">
                                    <h3 class="m-0">
                                        @lang('shopgo.product.feature.options.title')
                                    </h3>
                                    <div class="c-list-top-toolbar ms-auto">
                                        <button type="button" class="btn btn-sm btn-primary"
                                            @click="addNewItem()">
                                            <span class="fa fa-plus"></span>
                                            @lang('shopgo.product.feature.button.new')
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                            @click="removeItems()" :disabled="selected.length === 0">
                                            <span class="fa fa-trash"></span>
                                            @lang('shopgo.product.feature.button.delete')
                                        </button>
                                    </div>
                                </div>

                                <div class="c-option-list list-group list-group-flush">
                                    <draggable v-model="items" handle=".handle" item-key="uid">
                                        <template #item="{ element: item, index }">
                                            <div class="list-group-item c-option-item"
                                                :class="[{active: current === item}]"
                                                @click="selectItem(item)"
                                                style="cursor: pointer;"
                                            >
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="c-option-item__control">
                                                        <span class="fa fa-fw fa-ellipsis-v handle"
                                                            style="cursor: move"
                                                        ></span>
                                                        <input type="checkbox" name="selected[]" v-model="selected"
                                                            class="form-check-input"
                                                            :value="item.uid"
                                                            @click.stop="" />
                                                    </div>
                                                    <div v-if="type === 'color'" class="c-option-item__color">
                                                        <div class="c-option-item__color-box rounded"
                                                            style="width: 25px; height: 25px;"
                                                            :style="{'background-color': item.data.color || '#eee'}"></div>
                                                    </div>
                                                    <div class="c-option-control__title flex-grow-1">
                                                        <div class="h5 m-0">
                                                            @{{ item.data.text || '@lang('shopgo.product.feature.text.unnamed')' }}
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="c-option-control__actions d-flex align-items-center gap-1">
                                                        {{--<div class="mr-2" @click.stop="">--}}
                                                        {{--<label :for="'default-radio-' + item.uid">預設</label>--}}
                                                        {{--<input type="radio" name="item[default]" :value="item.uid"--}}
                                                        {{--:id="'default-radio-' + item.uid"--}}
                                                        {{--@click="setDefault(i)" />--}}
                                                        {{--</div>--}}
                                                        <button type="button"
                                                            class="btn btn-sm btn-light border-secondary"
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
                                                    <input type="hidden" :name="`options[${item.uid}][uid]`"
                                                        :value="item.data.uid" />
                                                    <input type="hidden" :name="`options[${item.uid}][text]`"
                                                        :value="item.data.text" />
                                                    <input type="hidden" :name="`options[${item.uid}][value]`"
                                                        :value="item.data.value" />
                                                    <input type="hidden" :name="`options[${item.uid}][color]`"
                                                        :value="item.data.color" />
                                                </div>
                                            </div>
                                        </template>
                                    </draggable>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 l-feature-option-item">
                            <div class="card c-option-edit">
                                <div class="card-header">
                                    @lang('shopgo.product.feature.option.data.title')
                                </div>
                                <div class="card-body">
                                    <div v-if="current" class="c-option-edit__form">
                                        <div class="form-group mb-4">
                                            <label for="input-option-text" class="form-label">
                                                @lang('shopgo.product.feature.option.text')
                                            </label>
                                            <input id="input-option-text" type="text" class="form-control"
                                                v-model="current.data.text" />
                                        </div>

                                        <div class="form-group mb-4">
                                            <label for="input-option-value" class="form-label">
                                                @lang('shopgo.product.feature.option.value')
                                            </label>
                                            <input id="input-option-value" type="text" class="form-control"
                                                v-model="current.data.value" />
                                        </div>

                                        <div class="form-group mb-4" v-if="type === 'color'">
                                            <label for="input-option-value" class="form-label">
                                                @lang('shopgo.product.feature.option.color')
                                            </label>
                                            <div>
                                                <input id="input-option-color" type="text"
                                                    v-colorpicker="colorpicker"
                                                    class="form-control"
                                                    v-model.lazy="current.data.color"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div v-else>
                                        <div class="card bg-light">
                                            <div class="card-body text-center">
                                                @lang('shopgo.product.feature.option.no.select')
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </product-feature-app>
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

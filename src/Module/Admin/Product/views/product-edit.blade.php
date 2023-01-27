<?php

declare(strict_types=1);

namespace App\View;

/**
 * Global variables
 * --------------------------------------------------------------
 * @var  $app       AppContext      Application context.
 * @var  $vm        ProductEditView  The view model object.
 * @var  $uri       SystemUri       System Uri information.
 * @var  $chronos   ChronosService  The chronos datetime service.
 * @var  $nav       Navigator       Navigator object to build route.
 * @var  $asset     AssetService    The Asset manage service.
 * @var  $lang      LangService     The language translation service.
 */

use App\Entity\Product;
use App\Module\Admin\Product\ProductEditView;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;
use Windwalker\Form\Form;

/**
 * @var Form      $form
 * @var Product $item
 */
?>

@extends('admin.global.body-edit')

@section('toolbar-buttons')
    @include('edit-toolbar')
@stop

@section('content')
    <form name="admin-form" id="admin-form"
        uni-form-validate='{"scroll": true}'
        action="{{ $nav->to('product_edit') }}"
        method="POST" enctype="multipart/form-data">

        <x-title-bar :form="$form"></x-title-bar>

        <div class="row">
            <div class="col-lg-9">

                <div class="card-header"
                    style="background-color: inherit">
                    <x-tabs keepactive variant="pills">
                        {{-- Tab: Info --}}
                        <x-tab-button target="#info" active>
                            @lang('shopgo.product.edit.tab.info')
                        </x-tab-button>
                        {{-- Tab: About --}}
                        <x-tab-button target="#about">
                            @lang('shopgo.product.edit.tab.about')
                        </x-tab-button>
                        {{-- Tab: Images --}}
                        <x-tab-button target="#images">
                            @lang('shopgo.product.edit.tab.images')
                        </x-tab-button>
                        {{-- Tab: Attributes --}}
                        <x-tab-button target="#attributes">
                            @lang('shopgo.product.edit.tab.attributes')
                        </x-tab-button>
                        {{-- Tab: Variants --}}
                        <x-tab-button target="#variants">
                            @lang('shopgo.product.edit.tab.variants')
                        </x-tab-button>
                        {{-- Tab: Discounts --}}
                        <x-tab-button target="#discounts">
                            @lang('shopgo.product.edit.tab.discounts')
                        </x-tab-button>
                    </x-tabs>
                </div>

                <div class="tab-content mt-4">
                    {{-- Pane: Info --}}
                    <x-tab-pane id="info" active>
                        <x-card>
                            <div class="row">
                                <div class="col-lg-4">
                                    <x-fieldset name="info1"
                                        :form="$form"
                                        class="mb-4"
                                        is="div"
                                    >
                                    </x-fieldset>
                                </div>
                                <div class="col-lg-5">
                                    <x-fieldset name="info2"
                                        :form="$form"
                                        class="mb-5"
                                        is="div"
                                    >
                                    </x-fieldset>
                                </div>
                                <div class="col-lg-3">
                                    <x-fieldset name="info3"
                                        :form="$form"
                                        class="mb-3"
                                        is="div"
                                    >
                                    </x-fieldset>
                                </div>
                            </div>
                        </x-card>
                    </x-tab-pane>

                    {{-- Pane: About --}}
                    <x-tab-pane id="about">
                        <div class="row">
                            <div class="col-lg-8">
                                <x-fieldset name="about"
                                    :form="$form"
                                    class="mb-4"
                                    is="card"
                                >
                                </x-fieldset>
                            </div>
                            <div class="col-lg-4">
                                <x-fieldset name="seo"
                                    :form="$form"
                                    class="mb-3"
                                    is="card"
                                >
                                </x-fieldset>
                            </div>
                        </div>
                    </x-tab-pane>

                    {{-- Pane: Images --}}
                    <x-tab-pane id="images">
                        <x-card>
                            <div class="row">
                                <div class="col-lg-10">
                                    <x-fieldset name="images"
                                        :form="$form"
                                        class="mb-4"
                                        is="div"
                                    >
                                    </x-fieldset>
                                </div>
                            </div>
                        </x-card>
                    </x-tab-pane>

                    {{-- Pane: Attributes --}}
                    <x-tab-pane id="attributes">
                    </x-tab-pane>

                    {{-- Pane: Variants --}}
                    <x-tab-pane id="variants">
                        @include('product-variants-edit')
                    </x-tab-pane>

                    {{-- Pane: Discounts --}}
                    <x-tab-pane id="discounts">
                        @include('product-discounts-edit')
                    </x-tab-pane>
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

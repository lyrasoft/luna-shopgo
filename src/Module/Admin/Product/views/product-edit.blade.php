<?php

declare(strict_types=1);

namespace App\View;

/**
 * Global variables
 * --------------------------------------------------------------
 * @var  $app       AppContext      Application context.
 * @var  $vm        \Lyrasoft\ShopGo\Module\Admin\Product\ProductEditView  The view model object.
 * @var  $uri       SystemUri       System Uri information.
 * @var  $chronos   ChronosService  The chronos datetime service.
 * @var  $nav       Navigator       Navigator object to build route.
 * @var  $asset     AssetService    The Asset manage service.
 * @var  $lang      LangService     The language translation service.
 */

use Lyrasoft\ShopGo\Module\Admin\Product\ProductEditView;
use Lyrasoft\ShopGo\Entity\Product;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;
use Windwalker\Form\Form;

/**
 * @var Form    $form
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

                <div id="edit-nav" class="card-header"
                    style="background-color: inherit">
                    {{-- Tab Buttons Here--}}
                </div>

                <x-tabs keepactive variant="pills"
                    nav-target="#edit-nav"
                    :nav-attrs="json_encode(['class' => 'mb-4'])"
                >
                    {{-- Pane: Info --}}
                    <x-tab name="info" active
                        :title="$lang('shopgo.product.edit.tab.info')"
                    >
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
                    </x-tab>

                    {{-- Pane: About --}}
                    <x-tab name="about"
                        :title="$lang('shopgo.product.edit.tab.about')">
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
                    </x-tab>

                    {{-- Pane: Images --}}
                    <x-tab name="images"
                        :title="$lang('shopgo.product.edit.tab.images')">
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
                    </x-tab>

                    {{-- Pane: Attributes --}}
                    <x-tab name="attributes"
                        :title="$lang('shopgo.product.edit.tab.attributes')">
                    </x-tab>

                    {{-- Pane: Variants --}}
                    <x-tab name="variants"
                        :title="$lang('shopgo.product.edit.tab.variants')">
                        @include('product-variants-edit')
                    </x-tab>

                    {{-- Pane: Discounts --}}
                    <x-tab name="discounts"
                        :title="$lang('shopgo.product.edit.tab.discounts')">
                        @include('product-discounts-edit')
                    </x-tab>
                </x-tabs>
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

<?php

declare(strict_types=1);

namespace App\View;

/**
 * Global variables
 * --------------------------------------------------------------
 * @var  $app       AppContext      Application context.
 * @var  $vm        \Lyrasoft\ShopGo\Module\Admin\ProductTab\ProductTabEditView  The view model object.
 * @var  $uri       SystemUri       System Uri information.
 * @var  $chronos   ChronosService  The chronos datetime service.
 * @var  $nav       Navigator       Navigator object to build route.
 * @var  $asset     AssetService    The Asset manage service.
 * @var  $lang      LangService     The language translation service.
 */

use Lyrasoft\ShopGo\Module\Admin\ProductTab\ProductTabEditView;
use Lyrasoft\ShopGo\Entity\ProductTab;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;
use Windwalker\Form\Form;

/**
 * @var Form       $form
 * @var ProductTab $item
 */
?>

@extends('admin.global.body-edit')

@section('toolbar-buttons')
    @include('edit-toolbar')
@stop

@section('content')
    <form name="admin-form" id="admin-form"
        uni-form-validate='{"scroll": true}'
        action="{{ $nav->to('product_tab_edit') }}"
        method="POST" enctype="multipart/form-data">

        <x-title-bar :form="$form"></x-title-bar>

        <div class="row">
            <div class="col-lg-7">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#basic">
                            @lang('unicorn.fieldset.basic')
                        </button>
                    </li>
                    <li class="nav-item">
                        <a type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#category">
                            @lang('shopgo.product.tab.field.category')
                        </a>
                    </li>
                </ul>

                <div class="tab-content pt-4" id="main-tab-content">
                    <div class="tab-pane fade show active" id="basic" role="tabpanel" tabindex="0">
                        <x-fieldset name="basic"
                            :form="$form"
                            class="mb-4"
                            is="div"
                        >
                        </x-fieldset>
                    </div>
                    <div class="tab-pane fade" id="category" role="tabpanel" tabindex="0">
                        <x-field :field="$form['categories']" no-label></x-field>
                    </div>
                </div>

            </div>
            <div class="col-lg-5">
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

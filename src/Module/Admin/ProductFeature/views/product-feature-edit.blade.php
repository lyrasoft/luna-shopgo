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

// $vueScript = $app->service(VueScript::class);
// $vueScript->vue();
// $vueScript->draggable();
// $vueScript->animate();

$uniScript = $app->service(UnicornScript::class);
$uniScript->translate('shopgo.product.feature.options.*');
$uniScript->translate('shopgo.product.feature.option.*');
$uniScript->translate('shopgo.product.feature.text.*');
$uniScript->translate('shopgo.product.feature.button.*');
$uniScript->data('options', $item?->options ?? []);
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
                <product-feature-edit-app id="product-feature-edit-app"></product-feature-edit-app>
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

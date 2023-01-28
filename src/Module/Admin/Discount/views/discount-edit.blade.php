<?php

declare(strict_types=1);

namespace App\View;

/**
 * Global variables
 * --------------------------------------------------------------
 * @var  $app       AppContext      Application context.
 * @var  $vm        DiscountEditView  The view model object.
 * @var  $uri       SystemUri       System Uri information.
 * @var  $chronos   ChronosService  The chronos datetime service.
 * @var  $nav       Navigator       Navigator object to build route.
 * @var  $asset     AssetService    The Asset manage service.
 * @var  $lang      LangService     The language translation service.
 */

use App\Entity\Discount;
use App\Module\Admin\Discount\DiscountEditView;
use Unicorn\Script\UnicornScript;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;
use Windwalker\Form\Form;

/**
 * @var Form     $form
 * @var Discount $item
 */

$uniScript = $app->service(UnicornScript::class);
$uniScript->addRoute('@discount_ajax');

?>

@extends('admin.global.body-edit')

@section('toolbar-buttons')
    @include('edit-toolbar')
@stop

@section('content')
    <form name="admin-form" id="admin-form"
        uni-form-validate='{"scroll": true}'
        action="{{ $nav->to('discount_edit') }}"
        method="POST" enctype="multipart/form-data">

        <x-title-bar :form="$form"></x-title-bar>

        <div class="row">
            <div class="col-lg-8">
                <x-tabs variant="pills" keepactive>
                    <x-tab name="basic" :title="$lang('shopgo.discount.edit.tab.basic')">
                        <x-fieldset :form="$form" name="basic" is="card" horizon="3:9">
                        </x-fieldset>
                    </x-tab>
                    <x-tab name="conditions" :title="$lang('shopgo.discount.edit.tab.conditions')">
                        <x-fieldset :form="$form" name="conditions" is="card" horizon="3:9">
                        </x-fieldset>
                    </x-tab>
                    <x-tab name="specifies" :title="$lang('shopgo.discount.edit.tab.specifies')">
                        <x-fieldset :form="$form" name="specifies" is="card" horizon="3:9">
                        </x-fieldset>
                    </x-tab>
                    <x-tab name="combine" :title="$lang('shopgo.discount.edit.tab.combine')">
                        <x-fieldset :form="$form" name="combine" is="card" horizon="3:9">
                        </x-fieldset>
                    </x-tab>
                    <x-tab name="pricing" :title="$lang('shopgo.discount.edit.tab.pricing')">
                        <x-fieldset :form="$form" name="pricing" is="card" horizon="3:9">
                        </x-fieldset>
                    </x-tab>
                </x-tabs>
            </div>
            <div class="col-lg-4">
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

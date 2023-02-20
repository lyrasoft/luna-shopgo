<?php

declare(strict_types=1);

namespace App\View;

/**
 * Global variables
 * --------------------------------------------------------------
 * @var  $app       AppContext      Application context.
 * @var  $vm        \Lyrasoft\ShopGo\Module\Admin\Shipping\ShippingEditView  The view model object.
 * @var  $uri       SystemUri       System Uri information.
 * @var  $chronos   ChronosService  The chronos datetime service.
 * @var  $nav       Navigator       Navigator object to build route.
 * @var  $asset     AssetService    The Asset manage service.
 * @var  $lang      LangService     The language translation service.
 */

use Lyrasoft\ShopGo\Module\Admin\Shipping\ShippingEditView;
use Lyrasoft\ShopGo\Entity\Shipping;
use Lyrasoft\ShopGo\Shipping\AbstractShipping;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;
use Windwalker\Form\Form;

/**
 * @var Form                           $form
 * @var Shipping                       $item
 * @var class-string<AbstractShipping> $typeClass
 * @var AbstractShipping               $typeInstance
 */

$tabs = $form->getFieldsets();
unset($tabs['meta']);

?>

@extends('admin.global.body-edit')

@section('toolbar-buttons')
    @include('edit-toolbar')
@stop

@section('content')
    <form name="admin-form" id="admin-form"
        uni-form-validate='{"scroll": true}'
        action="{{ $nav->to('shipping_edit') }}"
        method="POST" enctype="multipart/form-data">

        <x-title-bar :form="$form"></x-title-bar>

        <div class="mb-3">
            {!! $typeClass::getTypeDescription($lang) !!}
        </div>

        <div class="row">
            <div class="col-lg-7">
                <x-tabs keepactive variant="pills">
                    @foreach ($tabs as $tab)
                        <?php
                        if (!$tab->getTitle()) {
                            continue;
                        }
                        ?>
                        <x-tab :name="$tab->getName()" :title="$tab->getTitle()">
                            <x-fieldset
                                title=""
                                :name="$tab->getName()"
                                :form="$form"
                                class=""
                                is="card"
                            >
                            </x-fieldset>
                        </x-tab>
                    @endforeach
                </x-tabs>
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

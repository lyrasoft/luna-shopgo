<?php

declare(strict_types=1);

namespace App\View;

/**
 * Global variables
 * --------------------------------------------------------------
 * @var  $app       AppContext      Application context.
 * @var  $vm        \Lyraoft\ShopGo\Module\Admin\OrderState\OrderStateListView The view model object.
 * @var  $uri       SystemUri       System Uri information.
 * @var  $chronos   ChronosService  The chronos datetime service.
 * @var  $nav       Navigator       Navigator object to build route.
 * @var  $asset     AssetService    The Asset manage service.
 * @var  $lang      LangService     The language translation service.
 */

use Lyraoft\ShopGo\Module\Admin\OrderState\OrderStateListView;
use Lyraoft\ShopGo\Entity\OrderState;
use Unicorn\Workflow\BasicStateWorkflow;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;

/**
 * @var OrderState $entity
 */

$workflow = $app->service(BasicStateWorkflow::class);
?>

@extends('admin.global.body-list')

@section('toolbar-buttons')
    @include('list-toolbar')
@stop

@section('content')
    <form id="admin-form" action="" x-data="{ grid: $store.grid }"
        x-ref="gridForm"
        data-ordering="{{ $ordering }}"
        method="post">

        <x-filter-bar :form="$form" :open="$showFilters"></x-filter-bar>

        @if (count($items))
            {{-- RESPONSIVE TABLE DESC --}}
            <div class="d-block d-lg-none mb-3">
                @lang('unicorn.grid.responsive.table.desc')
            </div>

            <div class="grid-table table-lg-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        {{-- Toggle --}}
                        <th style="width: 1%">
                            <x-toggle-all></x-toggle-all>
                        </th>

                        {{-- Title --}}
                        <th class="text-nowrap">
                            <x-sort field="order_state.title">
                                @lang('unicorn.field.title')
                            </x-sort>
                        </th>

                        <th>
                            @lang('shopgo.order.state.field.color')
                        </th>

                        <th class="text-center" data-bs-toggle="tooltip"
                            title="@lang('shopgo.order.state.field.notice')">
                            <i class="fa fa-envelope"></i>
                        </th>

                        <th class="text-center" data-bs-toggle="tooltip"
                            title="@lang('shopgo.order.state.field.attach_invoice')">
                            <i class="fa fa-file-invoice"></i>
                        </th>

                        <th class="text-center"
                            data-bs-toggle="tooltip"
                            title="@lang('shopgo.order.state.field.shipped')">
                            <i class="fa fa-truck-fast"></i>
                        </th>

                        <th class="text-center"
                            data-bs-toggle="tooltip"
                            title="@lang('shopgo.order.state.field.paid')">
                            <i class="fa fa-money-check-dollar"></i>
                        </th>

                        <th class="text-center"
                            data-bs-toggle="tooltip"
                            title="@lang('shopgo.order.state.field.returned')">
                            <i class="fa fa-square-caret-left"></i>
                        </th>

                        <th class="text-center"
                            data-bs-toggle="tooltip"
                            title="@lang('shopgo.order.state.field.done')">
                            <i class="fa fa-thumbs-up"></i>
                        </th>

                        <th class="text-center"
                            data-bs-toggle="tooltip"
                            title="@lang('shopgo.order.state.field.cancel')">
                            <i class="fa fa-times"></i>
                        </th>

                        <th class="text-center"
                            data-bs-toggle="tooltip"
                            title="@lang('shopgo.order.state.field.rollback')">
                            <i class="fa fa-rotate-left"></i>
                        </th>

                        {{-- ORDERING --}}
                        <th style="width: 5%" class="text-nowrap">
                            <div class="d-flex w-100 justify-content-end">
                                <x-sort
                                    asc="order_state.ordering ASC"
                                    desc="order_state.ordering DESC"
                                >
                                    @lang('unicorn.field.ordering')
                                </x-sort>
                                @if($vm->reorderEnabled($ordering))
                                    <x-save-order class="ml-2 ms-2"></x-save-order>
                                @endif
                            </div>
                        </th>

                        {{-- Delete --}}
                        <th style="width: 1%" class="text-nowrap">
                            @lang('unicorn.field.delete')
                        </th>

                        {{-- ID --}}
                        <th style="width: 1%" class="text-nowrap text-end">
                            <x-sort field="order_state.id">
                                @lang('unicorn.field.id')
                            </x-sort>
                        </th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($items as $i => $item)
                        <?php
                        $entity = $vm->prepareItem($item);
                        ?>
                        <tr>
                            {{-- Checkbox --}}
                            <td>
                                <x-row-checkbox :row="$i" :id="$entity->getId()"></x-row-checkbox>
                            </td>

                            {{-- Title --}}
                            <td>
                                <div>
                                    <a href="{{ $nav->to('order_state_edit')->id($entity->getId()) }}">
                                        {{ $item->title }}
                                    </a>

                                    @if ($entity->isDefault())
                                        <i class="fa fa-star"
                                            data-bs-toggle="tooltip"
                                            title="@lang('shopgo.order.state.tooltip.default')"
                                        ></i>
                                    @endif
                                </div>
                            </td>

                            {{-- Color --}}
                            <td>
                                <span class="badge"
                                    style="background-color: {{ $entity->getColor() }}; color: {{ $entity->getContrastColor() }}">
                                    {{ $entity->getColor() }}
                                </span>
                            </td>

                            <td class="text-center">
                                @if ($entity->shouldAttachInvoice())
                                    <i class="fa fa-check text-success"></i>
                                @else
                                    -
                                @endif
                            </td>

                            <td class="text-center">
                                @if ($entity->shouldNotice())
                                    <i class="fa fa-check text-success"></i>
                                @else
                                    -
                                @endif
                            </td>

                            <td class="text-center">
                                @if ($entity->isShipped())
                                    <i class="fa fa-check text-success"></i>
                                @else
                                    -
                                @endif
                            </td>

                            <td class="text-center">
                                @if ($entity->isPaid())
                                    <i class="fa fa-check text-success"></i>
                                @else
                                    -
                                @endif
                            </td>

                            <td class="text-center">
                                @if ($entity->isReturned())
                                    <i class="fa fa-check text-success"></i>
                                @else
                                    -
                                @endif
                            </td>

                            <td class="text-center">
                                @if ($entity->isDone())
                                    <i class="fa fa-check text-success"></i>
                                @else
                                    -
                                @endif
                            </td>

                            <td class="text-center">
                                @if ($entity->isCancel())
                                    <i class="fa fa-check text-success"></i>
                                @else
                                    -
                                @endif
                            </td>

                            <td class="text-center">
                                @if ($entity->isRollback())
                                    <i class="fa fa-check text-success"></i>
                                @else
                                    -
                                @endif
                            </td>

                            {{-- Ordering --}}
                            <td class="text-end">
                                <x-order-control
                                    :enabled="$vm->reorderEnabled($ordering)"
                                    :row="$i"
                                    :id="$entity->getId()"
                                    :value="$item->ordering"
                                ></x-order-control>
                            </td>

                            {{-- Delete --}}
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                    @click="grid.deleteItem('{{ $entity->getId() }}')"
                                    data-dos
                                >
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </td>

                            {{-- ID --}}
                            <td class="text-end">
                                {{ $entity->getId() }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                    <tfoot>
                    <tr>
                        <td colspan="20">
                            {!! $pagination->render() !!}
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        @else
            <div class="grid-no-items card bg-light" style="padding: 125px 0;">
                <div class="card-body text-center">
                    <h3 class="text-secondary">@lang('unicorn.grid.no.items')</h3>
                </div>
            </div>
        @endif

        <div class="d-none">
            <input name="_method" type="hidden" value="PUT" />
            <x-csrf></x-csrf>
        </div>

        <x-batch-modal :form="$form" namespace="batch"></x-batch-modal>
    </form>

@stop

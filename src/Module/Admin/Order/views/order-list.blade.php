<?php

declare(strict_types=1);

namespace App\View;

/**
 * Global variables
 * --------------------------------------------------------------
 * @var  $app       AppContext      Application context.
 * @var  $vm        \Lyrasoft\ShopGo\Module\Admin\Order\OrderListView The view model object.
 * @var  $uri       SystemUri       System Uri information.
 * @var  $chronos   ChronosService  The chronos datetime service.
 * @var  $nav       Navigator       Navigator object to build route.
 * @var  $asset     AssetService    The Asset manage service.
 * @var  $lang      LangService     The language translation service.
 */

use Lyrasoft\ShopGo\Module\Admin\Order\OrderListView;
use Lyrasoft\ShopGo\Entity\Order;
use Lyrasoft\ShopGo\Entity\OrderState;
use Unicorn\Workflow\BasicStateWorkflow;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;
use Windwalker\ORM\ORM;

/**
 * @var Order       $entity
 * @var ?OrderState $state
 */

$workflow = $app->service(BasicStateWorkflow::class);

$orm = $app->service(ORM::class);
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

                        {{-- State --}}
                        <th style="width: 5%" class="text-nowrap">
                            <x-sort field="order.state_id">
                                @lang('unicorn.field.state')
                            </x-sort>
                        </th>

                        {{-- NO --}}
                        <th class="text-nowrap">
                            <x-sort field="order.no">
                                @lang('shopgo.order.field.no')
                            </x-sort>
                        </th>

                        {{-- User --}}
                        <th class="text-nowrap">
                            <x-sort field="order.user_id">
                                @lang('shopgo.order.field.user')
                            </x-sort>
                        </th>

                        {{-- Total --}}
                        <th class="text-nowrap text-end">
                            <x-sort field="order.total">
                                @lang('shopgo.order.field.total')
                            </x-sort>
                        </th>

                        <th class="text-center"
                            data-bs-toggle="tooltip"
                            title="@lang('shopgo.order.state.field.paid')">
                            <i class="fa fa-money-check-dollar"></i>
                        </th>

                        <th class="text-center"
                            data-bs-toggle="tooltip"
                            title="@lang('shopgo.order.state.field.shipped')">
                            <i class="fa fa-truck-fast"></i>
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
                            title="@lang('shopgo.order.state.field.returned')">
                            <i class="fa fa-square-caret-left"></i>
                        </th>

                        <th class="text-center"
                            data-bs-toggle="tooltip"
                            title="@lang('shopgo.order.state.field.rollback')">
                            <i class="fa fa-rotate-left"></i>
                        </th>

                        {{-- Created --}}
                        <th class="text-nowrap">
                            <x-sort field="order.created">
                                @lang('unicorn.field.created')
                            </x-sort>
                        </th>

                        {{-- Delete --}}
                        <th style="width: 1%" class="text-nowrap">
                            @lang('unicorn.field.delete')
                        </th>

                        {{-- ID --}}
                        <th style="width: 1%" class="text-nowrap text-end">
                            <x-sort field="order.id">
                                @lang('unicorn.field.id')
                            </x-sort>
                        </th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($items as $i => $item)
                        <?php
                        $entity = $vm->prepareItem($item);

                        if ($item->order_state?->id) {
                            $state = $orm->toEntity(OrderState::class, $item->order_state);
                        } else {
                            $state = null;
                        }
                        ?>
                        <tr>
                            {{-- Checkbox --}}
                            <td>
                                <x-row-checkbox :row="$i" :id="$entity->getId()"></x-row-checkbox>
                            </td>

                            {{-- State --}}
                            <td>
                                <span class="badge px-3 py-2 w-100"
                                    style="{{ $state?->getColorCSS() ?: 'background: var(--bs-dark)' }} font-size: .9375rem;">
                                    {{ $state?->getTitle() ?: $entity->getStateText() }}
                                </span>
                            </td>

                            {{-- Title --}}
                            <td>
                                <div>
                                    <a href="{{ $nav->to('order_edit')->id($entity->getId()) }}">
                                        #{{ $entity->getNo() }}
                                    </a>
                                </div>
                            </td>

                            {{-- User --}}
                            <td class="text-nowrap">
                                {{ $entity->getPaymentData()->getFullName() }}
                            </td>

                            {{-- Total --}}
                            <td class="text-end">
                                {{ $vm->formatPrice($entity->getTotal()) }}
                            </td>

                            <td class="text-center">
                                @if ($entity->getPaidAt())
                                    <div data-bs-toggle="tooltip"
                                        title="{{ $chronos->toLocalFormat($entity->getPaidAt()) }}">
                                        <i class="fa fa-clock text-info"></i>
                                    </div>
                                @else
                                    -
                                @endif
                            </td>

                            <td class="text-center">
                                @if ($entity->getShippedAt())
                                    <div data-bs-toggle="tooltip"
                                        title="{{ $chronos->toLocalFormat($entity->getShippedAt()) }}">
                                        <i class="fa fa-clock text-info"></i>
                                    </div>
                                @else
                                    -
                                @endif
                            </td>

                            <td class="text-center">
                                @if ($entity->getDoneAt())
                                    <div data-bs-toggle="tooltip"
                                        title="{{ $chronos->toLocalFormat($entity->getDoneAt()) }}">
                                        <i class="fa fa-clock text-success"></i>
                                    </div>
                                @else
                                    -
                                @endif
                            </td>

                            <td class="text-center">
                                @if ($entity->getCancelledAt())
                                    <div data-bs-toggle="tooltip"
                                        title="{{ $chronos->toLocalFormat($entity->getCancelledAt()) }}">
                                        <i class="fa fa-clock text-secondary"></i>
                                    </div>
                                @else
                                    -
                                @endif
                            </td>

                            <td class="text-center">
                                @if ($entity->getReturnedAt())
                                    <div data-bs-toggle="tooltip"
                                        title="{{ $chronos->toLocalFormat($entity->getReturnedAt()) }}">
                                        <i class="fa fa-clock text-danger"></i>
                                    </div>
                                @else
                                    -
                                @endif
                            </td>

                            <td class="text-center">
                                @if ($entity->getRollbackAt())
                                    <div data-bs-toggle="tooltip"
                                        title="{{ $chronos->toLocalFormat($entity->getRollbackAt()) }}">
                                        <i class="fa fa-clock text-warning"></i>
                                    </div>
                                @else
                                    -
                                @endif
                            </td>

                            {{-- Created --}}
                            <td>
                                <div data-bs-toggle="tooltip"
                                    title="{{ $chronos->toLocalFormat($entity->getCreated()) }}">
                                    {{ $chronos->toLocalFormat($entity->getCreated(), 'Y-m-d') }}
                                </div>
                            </td>

                            @can('superuser')
                                {{-- Delete --}}
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                        @click="grid.deleteItem('{{ $entity->getId() }}')"
                                        data-dos
                                    >
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            @endcan

                            {{-- ID --}}
                            <td class="text-end">
                                {{ $entity->getId() }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div>
                    <x-pagination :pagination="$pagination"></x-pagination>
                </div>
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

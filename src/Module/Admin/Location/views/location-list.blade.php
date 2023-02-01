<?php

declare(strict_types=1);

namespace App\View;

/**
 * Global variables
 * --------------------------------------------------------------
 * @var  $app       AppContext      Application context.
 * @var  $vm        \Lyraoft\ShopGo\Module\Admin\Location\LocationListView The view model object.
 * @var  $uri       SystemUri       System Uri information.
 * @var  $chronos   ChronosService  The chronos datetime service.
 * @var  $nav       Navigator       Navigator object to build route.
 * @var  $asset     AssetService    The Asset manage service.
 * @var  $lang      LangService     The language translation service.
 */

use Lyraoft\ShopGo\Module\Admin\Location\LocationListView;
use Lyraoft\ShopGo\Entity\Location;
use Unicorn\Html\Breadcrumb;
use Unicorn\Workflow\BasicStateWorkflow;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;
use Windwalker\Data\Collection;

/**
 * @var Location   $entity
 * @var Collection $parents
 * @var Location   $parent
 * @var Location   $current
 */

$workflow = $app->service(BasicStateWorkflow::class);

$breadcrumb = $app->service(Breadcrumb::class);
$breadcrumb->push(
    $lang('shopgo.location.root'),
    $nav->self()->var('current_id', 0)
);

$parents->shift();
$parentsCount = count($parents);

foreach ($parents as $i => $parent) {
    $breadcrumb->push(
        $parent->getTitle(),
        ($i + 1) !== $parentsCount
            ? $nav->self()->var('current_id', $parent->getId())
            : null
    );
}

$orders = [];
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

        <x-filter-bar :form="$form" :open="$showFilters">
            <x-slot name="end">
                @if (!$current->isRoot())
                    <div class="d-flex gap-3">
                        <a href="{{ $nav->self()->var('current_id', $current->getParentId()) }}"
                            class="btn btn-outline-primary btn-sm">
                            <i class="fa fa-chevron-left"></i>
                            @lang('shopgo.location.button.back')
                        </a>
                        {!! $breadcrumb->render() !!}
                    </div>
                @endif
            </x-slot>
        </x-filter-bar>

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
                            <x-sort field="location.state">
                                @lang('unicorn.field.state')
                            </x-sort>
                        </th>

                        {{-- Type --}}
                        <th style="width: 5%" class="text-nowrap">
                            @lang('unicorn.field.type')
                        </th>

                        {{-- Title --}}
                        <th class="text-nowrap">
                            <x-sort field="location.title">
                                @lang('unicorn.field.title')
                            </x-sort>
                        </th>

                        {{-- Native --}}
                        <th class="text-nowrap">
                            <x-sort field="location.native">
                                @lang('shopgo.location.field.native')
                            </x-sort>
                        </th>

                        {{-- Code --}}
                        <th class="text-nowrap">
                            <x-sort field="location.code">
                                @lang('shopgo.location.field.code')
                            </x-sort>
                        </th>

                        <th>

                        </th>

                        {{-- Ordering --}}
                        <th style="width: 10%" class="text-nowrap">
                            <div class="d-flex w-100 justify-content-end">
                                <x-sort
                                    asc="location.ordering ASC"
                                    desc="location.ordering DESC"
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
                            <x-sort field="location.id">
                                @lang('unicorn.field.id')
                            </x-sort>
                        </th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($items as $i => $item)
                        <?php
                        $entity = $vm->prepareItem($item);

                        $orders[$entity->getParentId()][] = $entity->getId();
                        $order = count($orders[$entity->getParentId()]);
                        ?>
                        <tr>
                            {{-- Checkbox --}}
                            <td>
                                <x-row-checkbox :row="$i" :id="$entity->getId()"></x-row-checkbox>
                            </td>

                            {{-- State --}}
                            <td>
                                <x-state-dropdown color-on="text"
                                    button-style="width: 100%"
                                    use-states
                                    :workflow="$workflow"
                                    :id="$entity->getId()"
                                    :value="$item->state"
                                ></x-state-dropdown>
                            </td>

                            {{-- Type --}}
                            <td>
                                {{ $entity->getType()->getTitle($lang) }}
                            </td>

                            {{-- Title --}}
                            <td class="">
                                <div class="d-flex">
                                    <div>
                                        <a href="{{ $nav->to('location_edit')->id($entity->getId()) }}">
                                            <i class="fa fa-edit small"></i>
                                            {{ $item->title }}
                                        </a>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div class="text-secondary ms-2">
                                    {{ $entity->getNative() ?: '-' }}
                                </div>
                            </td>

                            <td>
                                <?php
                                $codes = [$entity->getCode(), $entity->getCode3()];
                                echo implode(' / ', array_filter($codes));
                                ?>
                            </td>

                            <td style="width: 3%" class="text-nowrap">
                                @if ($entity->getRgt() - $entity->getLft() > 1)
                                    <div class="ms-auto ml-auto">
                                        <a href="{{ $nav->self()->var('current_id', $entity->getId()) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fa fa-list"></i>
                                            @lang('shopgo.location.see.children')
                                        </a>
                                    </div>
                                @endif
                            </td>

                            {{-- Ordering --}}
                            <td class="text-end text-right">
                                <x-order-control
                                    :enabled="$vm->reorderEnabled($ordering)"
                                    :row="$i"
                                    :id="$entity->getId()"
                                    :value="$order"
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

<?php

declare(strict_types=1);

namespace App\View;

/**
 * Global variables
 * --------------------------------------------------------------
 * @var  $app       AppContext      Application context.
 * @var  $vm        \Lyrasoft\ShopGo\Module\Admin\Product\ProductListView The view model object.
 * @var  $uri       SystemUri       System Uri information.
 * @var  $chronos   ChronosService  The chronos datetime service.
 * @var  $nav       Navigator       Navigator object to build route.
 * @var  $asset     AssetService    The Asset manage service.
 * @var  $lang      LangService     The language translation service.
 */

use Lyrasoft\ShopGo\Module\Admin\Product\ProductListView;
use Lyrasoft\ShopGo\Entity\Product;
use Unicorn\Image\ImagePlaceholder;
use Unicorn\Workflow\BasicStateWorkflow;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;

/**
 * @var Product $entity
 */

$workflow = $app->service(BasicStateWorkflow::class);
$imagePlaceholder = $app->service(ImagePlaceholder::class);

$defaultImage = $imagePlaceholder->placeholderSquare();
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
                            <x-sort field="product.state">
                                @lang('unicorn.field.state')
                            </x-sort>
                        </th>

                        <th class="text-nowrap" style="width: 3%">
                            @lang('shopgo.product.field.cover')
                        </th>

                        {{-- Title --}}
                        <th class="text-nowrap">
                            <x-sort field="product.title">
                                @lang('unicorn.field.title')
                            </x-sort>
                            /
                            <x-sort field="variants.count">
                                @lang('shopgo.product.field.variants')
                            </x-sort>
                        </th>

                        {{-- Model --}}
                        <th class="text-nowrap">
                            <x-sort field="product.model">
                                @lang('shopgo.product.field.model')
                            </x-sort>
                        </th>

                        {{-- Price --}}
                        <th class="text-nowrap text-end">
                            <x-sort field="variant.price">
                                @lang('shopgo.product.field.price')
                            </x-sort>
                        </th>

                        {{-- Stock --}}
                        <th class="text-nowrap text-end">
                            <x-sort field="variant.stock_quantity">
                                @lang('shopgo.product.field.stock.quantity')
                            </x-sort>
                        </th>

                        @if ($hasCategoryFilter)
                            {{-- Ordering --}}
                            <th style="width: 10%" class="text-nowrap">
                                <div class="d-flex w-100 justify-content-end">
                                    <x-sort
                                        asc="product.ordering ASC"
                                        desc="product.ordering DESC"
                                    >
                                        @lang('unicorn.field.ordering')
                                    </x-sort>
                                    @if($vm->reorderEnabled($ordering))
                                        <x-save-order class="ml-2 ms-2"></x-save-order>
                                    @endif
                                </div>
                            </th>
                        @endif

                        {{-- Delete --}}
                        <th style="width: 1%" class="text-nowrap">
                            @lang('unicorn.field.delete')
                        </th>

                        {{-- ID --}}
                        <th style="width: 1%" class="text-nowrap text-end">
                            <x-sort field="product.id">
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

                            <td>
                                <img style="height: 55px;" src="{{ $item->variant->cover }}" alt="cover">
                            </td>

                            {{-- Title --}}
                            <td>
                                <div class="d-flex align-items-center gap-1">
                                    <a class="fs-5"
                                        href="{{ $nav->to('product_edit')->id($entity->getId()) }}">
                                        {{ $item->title }}
                                    </a>

                                    @if ($item->variants_count > 0)
                                        <div class="small">
                                            <div class="badge bg-secondary rounded-pill">
                                                {{ $item->variants_count }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="mt-1 small text-muted">
                                    <i class="fa fa-folder"></i>
                                    <span>
                                        {{ $item->category?->title }}
                                    </span>
                                </div>

                                @if ($item->variant->sku)
                                    <div class="mt-1 small text-muted"
                                        data-bs-toggle="tooltip"
                                        title="@lang('shopgo.product.field.sku')"
                                    >
                                        #{{ $item->variant->sku }}
                                    </div>
                                @endif
                            </td>

                            {{-- Model --}}
                            <td class="text-nowrap">
                                {{ $entity->getModel() ?: '-' }}
                            </td>

                            {{-- Price --}}
                            <td class="text-end">
                                @if ($item->variants_count < 1)
                                    {{ $vm->formatPrice((float) $item->variant->price) }}
                                @elseif ($item->min_price === $item->max_price)
                                    {{ $vm->formatPrice((float) $item->max_price) }}
                                @else
                                    {{ $vm->formatPrice((float) $item->min_price) }}
                                    -
                                    {{ $vm->formatPrice((float) $item->max_price) }}
                                @endif
                            </td>

                            {{-- Stock --}}
                            <td class=" text-end">
                                <?php
                                $qty = (int) $item->total_stock_quantity;
                                if ($qty === 0) {
                                    $color = 'danger';
                                } else {
                                    $color = 'success';
                                }
                                ?>
                                <span class="badge bg-{{ $color }}">
                                    {{ number_format((float) $qty) }}
                                </span>
                            </td>

                            @if ($hasCategoryFilter)
                                {{-- Ordering --}}
                                <td class="text-end text-right">
                                    <x-order-control
                                        :enabled="$vm->reorderEnabled($ordering)"
                                        :row="$i"
                                        :id="$entity->category_map->id"
                                        :value="$entity->category_map->ordering"
                                    ></x-order-control>
                                </td>
                            @endif

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

<?php

declare(strict_types=1);

namespace App\View;

/**
 * Global variables
 * --------------------------------------------------------------
 * @var  $app       AppContext      Application context.
 * @var  $vm        \Lyraoft\ShopGo\Module\Admin\AdditionalPurchase\AdditionalPurchaseListView The view model object.
 * @var  $uri       SystemUri       System Uri information.
 * @var  $chronos   ChronosService  The chronos datetime service.
 * @var  $nav       Navigator       Navigator object to build route.
 * @var  $asset     AssetService    The Asset manage service.
 * @var  $lang      LangService     The language translation service.
 */

use Lyraoft\ShopGo\Module\Admin\AdditionalPurchase\AdditionalPurchaseListView;
use Lyraoft\ShopGo\Entity\AdditionalPurchase;
use Lyraoft\ShopGo\Entity\Product;
use Lyraoft\ShopGo\Entity\ProductVariant;
use Unicorn\Image\ImagePlaceholder;
use Unicorn\Workflow\BasicStateWorkflow;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;

/**
 * @var AdditionalPurchase $entity
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
                            <x-sort field="additional_purchase.state">
                                @lang('unicorn.field.state')
                            </x-sort>
                        </th>

                        {{-- Image --}}
                        <th class="text-nowrap" style="width: 3%;">
                            @lang('shopgo.product.field.cover')
                        </th>

                        {{-- Title --}}
                        <th class="text-nowrap">
                            <x-sort field="additional_purchase.title">
                                @lang('unicorn.field.title')
                            </x-sort>
                        </th>

                        {{-- Price --}}
                        <th class="text-nowrap text-end">
                            <x-sort field="additional_purchase.price">
                                @lang('shopgo.product.field.price')
                            </x-sort>
                        </th>

                        {{-- Count --}}
                        <th class="text-nowrap text-end">
                            <x-sort field="map.count">
                                @lang('shopgo.additional.purchase.field.targets.count')
                            </x-sort>
                        </th>

                        {{-- Ordering --}}
                        <th style="width: 10%" class="text-nowrap">
                            <div class="d-flex w-100 justify-content-end">
                                <x-sort
                                    asc="additional_purchase.ordering ASC"
                                    desc="additional_purchase.ordering DESC"
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
                            <x-sort field="additional_purchase.id">
                                @lang('unicorn.field.id')
                            </x-sort>
                        </th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($items as $i => $item)
                        <?php
                        $entity = $vm->prepareItem($item);
                        $variant = $vm->toEntity(ProductVariant::class, $item->variant);
                        $product = $vm->toEntity(Product::class, $item->product);
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
                                <img style="height: 55px;"
                                    src="{{ $item->variant->cover ?: $defaultImage }}"
                                    alt="Cover">
                            </td>

                            {{-- Title --}}
                            <td>
                                <div>
                                    <a href="{{ $nav->to('additional_purchase_edit')->id($entity->getId()) }}">
                                        {{ $item->title }}
                                    </a>
                                </div>

                                <div class="mt-1 small text-muted">
                                    {{ $product->getTitle() }}
                                    @if (!$variant->isPrimary())
                                        <i class="fa fa-arrow-right"></i>
                                        {{ $variant->getTitle() }}
                                    @endif
                                </div>
                            </td>

                            {{-- Price --}}
                            <td class="text-end">
                                {{ $vm->formatPrice($entity->getPrice()) }}
                            </td>

                            {{-- Count --}}
                            <td class="text-end">
                                <span class="badge bg-primary">
                                    {{ $item->target_product_counts ?: 0 }}
                                </span>
                            </td>

                            {{-- Ordering --}}
                            <td class="text-end text-right">
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

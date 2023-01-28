<?php

declare(strict_types=1);

namespace App\View;

/**
 * Global variables
 * --------------------------------------------------------------
 * @var $app       AppContext      Application context.
 * @var $vm        ProductListView  The view model object.
 * @var $uri       SystemUri       System Uri information.
 * @var $chronos   ChronosService  The chronos datetime service.
 * @var $nav       Navigator       Navigator object to build route.
 * @var $asset     AssetService    The Asset manage service.
 * @var $lang      LangService     The language translation service.
 */

use App\Module\Admin\Product\ProductListView;
use Unicorn\Workflow\BasicStateWorkflow;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;

$callback = $app->input('callback');
$workflow = $app->service(BasicStateWorkflow::class);
?>

@extends('admin.global.pure')

@section('body')
    <form id="admin-form" action="" x-data="{ grid: $store.grid }"
        x-ref="gridForm"
        data-ordering="{{ $ordering }}"
        method="post">

        <x-filter-bar :form="$form" :open="$showFilters"></x-filter-bar>

        <div>
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th style="width: 10%">
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

                    <th class="text-end text-nowrap" style="width: 1%">
                        <x-sort field="product.id">
                            @lang('unicorn.field.id')
                        </x-sort>
                    </th>
                </tr>
                </thead>

                <tbody>
                @foreach($items as $i => $item)
                    @php($data = [
                        'title' => $item->title,
                        'value' => $item->id,
                        'image' => $item->variant->cover,
                    ])
                    <tr>
                        <td>
                            <x-state-dropdown color-on="text"
                                button-style="width: 100%"
                                use-states
                                readonly
                                :workflow="$workflow"
                                :id="$item->id"
                                :value="$item->state"
                            ></x-state-dropdown>
                        </td>

                        <td>
                            <img style="height: 55px;" src="{{ $item->variant->cover }}" alt="cover">
                        </td>

                        {{-- Title --}}
                        <td>
                            <div class="d-flex align-items-center gap-1">
                                <a href="javascript://" class="fs-5"
                                    onclick="parent.{{ $callback }}({{ json_encode($data) }})">
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

                        <td class="text-end">
                            {{ $item->id }}
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

        <div class="d-none">
            <input name="_method" type="hidden" value="PUT" />
            <x-csrf></x-csrf>
        </div>

        <x-batch-modal :form="$form" namespace="batch"></x-batch-modal>
    </form>

@stop

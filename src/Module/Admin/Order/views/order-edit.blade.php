<?php

declare(strict_types=1);

namespace App\View;

/**
 * Global variables
 * --------------------------------------------------------------
 * @var  $app       AppContext      Application context.
 * @var  $vm        OrderEditView  The view model object.
 * @var  $uri       SystemUri       System Uri information.
 * @var  $chronos   ChronosService  The chronos datetime service.
 * @var  $nav       Navigator       Navigator object to build route.
 * @var  $asset     AssetService    The Asset manage service.
 * @var  $lang      LangService     The language translation service.
 */

use App\Cart\Price\PriceSet;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\OrderTotal;
use App\Module\Admin\Order\OrderEditView;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;
use Windwalker\Form\Form;

/**
 * @var Form        $form
 * @var Order       $item
 * @var OrderItem[] $orderItems
 * @var PriceSet  $totals
 */

$alert = $item->getParams()['alert'] ?? [];

$workflow = $app->service(\App\Workflow\OrderStateWorkflow::class);

$alert = $item->getParams()['alert'] ?? [];
?>

@extends('admin.global.body-edit')

@section('toolbar-buttons')
    @include('edit-toolbar')
@stop

@section('content')
    <form name="admin-form" id="admin-form"
        uni-form-validate='{"scroll": true}'
        action="{{ $nav->to('order_edit') }}"
        method="POST" enctype="multipart/form-data">

        @if ($alert)
            @foreach ($alert as $msg)
                <div class="alert alert-warning">
                    {{ $msg }}
                </div>
            @endforeach
        @endif

        <div class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header fw-bold">
                            訂單資訊
                        </div>
                        <div class="card-body">
                            <dl class="row p-2 mb-0">
                                <dt class="col-4">
                                    訂單編號
                                </dt>
                                <dd class="col-8">
                                    #{{ $item->getNo() }}
                                </dd>
                                <dt class="col-4">
                                    訂單狀態
                                </dt>
                                <dd class="col-8">
                                        <?php
                                    $state = $item->getState();
                                    $bg = $state->getColor();
                                    $color = $state->getContrastColor();
                                    ?>
                                    <span class="badge px-2 py-1"
                                        style="font-size: .875rem; background-color: {{ $bg }}; color: {{ $color }}">
                                        {{ $item->getState()->getTitle() }}
                                    </span>
                                </dd>
                                <dt class="col-4">
                                    購買日期
                                </dt>
                                <dd class="col-8">
                                    {{ $chronos->toLocalFormat($item->getCreated()) }}
                                </dd>
                                <dt class="col-4">
                                    付款日期
                                </dt>
                                <dd class="col-8">
                                    @if ($item->getPaidAt())
                                        {{ $chronos->toLocalFormat($item->getPaidAt()) }}
                                    @else
                                        -
                                    @endif
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                        <?php
                    $paymentData = $item->getPaymentData();
                    ?>
                    <div class="card">
                        <div class="card-header fw-bold">
                            會員資訊
                        </div>
                        <div class="card-body">
                            <dl class="row p-2 mb-0">
                                <dt class="col-4">
                                    訂購人
                                </dt>
                                <dd class="col-8">
                                    {{ $paymentData->getName() }}
                                </dd>
                                <dt class="col-4">
                                    手機號碼
                                </dt>
                                <dd class="col-8">
                                    {{ $paymentData->getMobile() }}
                                </dd>
                                <dt class="col-4">
                                    信箱
                                </dt>
                                <dd class="col-8">
                                    {{ $paymentData->getEmail() ?: '-' }}
                                </dd>
                                <dt class="col-4">
                                    備註
                                </dt>
                                <dd class="col-8">
                                    {{ $item->getNote() ?: '-' }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header fw-bold">
                            配送資訊
                        </div>
                        <div class="card-body">
                            <dl class="row p-2 mb-0">
                                <dt class="col-4">
                                    配送方式
                                </dt>
                                <dd class="col-8">
                                    {{ $item->getShipping()->getTitle() }}
                                </dd>

                                <dt class="col-4">
                                    付款方式
                                </dt>
                                <dd class="col-8">
                                    {{ $item->getPayment()->getTitle() }}
                                </dd>

                                <dt class="col-4">
                                    收據編號
                                </dt>
                                <dd class="col-8">
                                    {{ $item->getInvoiceNo() ?: '-' }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Order Items--}}
        <div class="l-order-items card mb-5">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>商品</th>
                    <th>數量</th>
                    <th>價格</th>
                </tr>
                </thead>
                <tbody>
                @foreach($orderItems as $orderItem)
                    <tr>
                        <td>{{ $orderItem->getId() }}</td>
                        <td>
                            <div class="d-flex align-items-start">
                                <img src="{{ $orderItem->getImage() }}" class="me-3" width="100"
                                    alt="Image">
                                <div>{{ $orderItem->getTitle() }}</div>
                            </div>
                        </td>
                        <td style="width: 200px">{{ $orderItem->getQuantity() }}</td>
                        <td class="text-end" style="width: 200px">
                            {{ $vm->formatPrice($orderItem->getTotal()) }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="text-end mb-5">
                <table class="table">
                    <tr data-type="{{ $totals['total']->getParamValue('code') }}" data-discount-id="{{ $totals['total']->getParamValue('discountId') }}">
                        <th style="border: none">
                            <div>
                                {{ $totals['total']->getLabel() }}
                            </div>
                        </th>
                        <td style="border: none; vertical-align: middle" width="150px">
                            {{ $vm->formatPrice($totals['total']) }}
                        </td>
                    </tr>

                    @foreach ($totals as $total)
                        @if (!str_starts_with($total->getName(), 'discount:'))
                            @continue
                        @endif

                        <tr data-type="{{ $total->getParamValue('code') }}" data-discount-id="{{ $total->getParamValue('discountId') }}">
                            <th style="border: none">
                                <div>
                                    {{ $total->getLabel() }}
                                </div>
                            </th>
                            <td style="border: none; vertical-align: middle" width="150px">
                                {{ $vm->formatPrice($total) }}
                            </td>
                        </tr>
                    @endforeach

                    @if (!$totals['shipping_fee']->isZero())
                        <tr data-type="{{ $totals['shipping_fee']->getParamValue('code') }}" data-discount-id="{{ $totals['shipping_fee']->getParamValue('discountId') }}">
                            <th style="border: none">
                                <div>
                                    {{ $totals['shipping_fee']->getLabel() }}
                                </div>
                            </th>
                            <td style="border: none; vertical-align: middle" width="150px">
                                {{ $vm->formatPrice($totals['shipping_fee']) }}
                            </td>
                        </tr>
                    @endif

                    @if ($totals->has('free_shipping'))
                        @if(!$totals['free_shipping']->isZero())
                            <tr data-type="{{ $totals['free_shipping']->getParamValue('code') }}" data-discount-id="{{ $totals['free_shipping']->getParamValue('discountId') }}">
                                <th style="border: none">
                                    <div>
                                        {{ $totals['free_shipping']->getLabel() }}
                                    </div>
                                </th>
                                <td style="border: none; vertical-align: middle" width="150px">
                                    {{ $vm->formatPrice($totals['free_shipping']) }}
                                </td>
                            </tr>
                        @endif
                    @endif

                    <tr data-type="{{ $totals['grand_total']->getParamValue('code') }}" data-discount-id="{{ $totals['grand_total']->getParamValue('discountId') }}">
                        <th style="border: none">
                            <div>
                                {{ $totals['grand_total']->getLabel() }}
                            </div>
                        </th>
                        <td style="border: none; vertical-align: middle" width="150px">
                            {{ $vm->formatPrice($totals['grand_total']) }}
                        </td>
                    </tr>
                </table>

            </div>
        </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header fw-bold">
                            訂單歷史訊息
                        </div>

                        <div class="card-body">
                            <div class="d-flex justify-content-end mb-3">
                                <a href="javascript://" class="btn btn-info"
                                    data-bs-toggle="modal"
                                    data-bs-target="#order-state-modal-{{ $item->getId() }}"
                                >
                                    變更狀態
                                </a>
                            </div>

                            <x-order-histories :histories="$histories"></x-order-histories>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">

                </div>
            </div>

        <div class="d-none">
            @if ($idField = $form?->getField('id'))
                <input name="{{ $idField->getInputName() }}" type="hidden" value="{{ $idField->getValue() }}" />
            @endif

            <x-csrf></x-csrf>
        </div>
    </form>

    <x-shopgo.order-info.order-state-modal :order="$item"></x-shopgo.order-info.order-state-modal>
@stop

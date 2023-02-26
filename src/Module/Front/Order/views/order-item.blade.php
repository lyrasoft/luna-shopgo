<?php

declare(strict_types=1);

namespace App\View;

/**
 * Global variables
 * --------------------------------------------------------------
 * @var  $app       AppContext      Application context.
 * @var  $vm        \Lyrasoft\ShopGo\Module\Front\Order\OrderItemView  The view model object.
 * @var  $uri       SystemUri       System Uri information.
 * @var  $chronos   ChronosService  The chronos datetime service.
 * @var  $nav       Navigator       Navigator object to build route.
 * @var  $asset     AssetService    The Asset manage service.
 * @var  $lang      LangService     The language translation service.
 */

use Lyrasoft\ShopGo\Cart\Price\PriceSet;
use Lyrasoft\ShopGo\Entity\Order;
use Lyrasoft\ShopGo\Entity\OrderHistory;
use Lyrasoft\ShopGo\Entity\OrderItem;
use Lyrasoft\ShopGo\Entity\OrderState;
use Lyrasoft\ShopGo\Enum\OrderHistoryType;
use Lyrasoft\ShopGo\Module\Front\Order\OrderItemView;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;
use Windwalker\ORM\ORM;

/**
 * @var Order          $item
 * @var OrderItem[]    $orderItems
 * @var PriceSet       $totals
 * @var OrderHistory[] $histories
 */

$orm = $app->service(ORM::class);
?>

@extends('global.body')

@section('content')
    <div class="container l-my-order my-5">
        <div class="l-my-order__info d-flex flex-column gap-4">
            <div class="row">
                <div class="col-md-4 mb-4 mb-lg-0">
                    <x-order-info.col1 :order="$item"></x-order-info.col1>
                </div>
                <div class="col-md-4 mb-4 mb-lg-0">
                    <x-order-info.col2 :order="$item"></x-order-info.col2>
                </div>
                <div class="col-md-4 mb-4 mb-lg-0">
                    <x-order-info.col3 :order="$item"></x-order-info.col3>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 l-my-order__payment mb-4 mb-lg-0">
                    <x-order-info.payment-data :order="$item"></x-order-info.payment-data>
                </div>
                <div class="col-md-6 l-my-order__shipping mb-4 mb-lg-0">
                    <x-order-info.shipping-data :order="$item"></x-order-info.shipping-data>
                </div>
            </div>

            <div class="l-my-order__items">
                {{-- Order Items--}}
                <x-order-items
                    :order="$item"
                    :order-items="$orderItems"
                    :attachments="$attachments"
                    :totals="$totals"
                ></x-order-items>
            </div>
        </div>

        <div class="l-order-history l-my-order__history card">
            <div class="l-order-history__header card-header">
                @lang('shopgo.order.field.histories')
            </div>

            <div class="l-order-history__content table-responsive">
                <table class="table mb-0">
                    <thead>
                    <tr>
                        <th>更新日期</th>
                        <th class="text-nowrap">訂單狀況</th>
                        <th>備註</th>
                        {{--                        <th class="text-nowrap">發信通知</th>--}}
                        <th class="text-nowrap">操作人員</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($histories as $history)
                            <?php
                            if ($history->order_state->id) {
                                $state = $orm->toEntity(OrderState::class, $history->order_state);
                            } else {
                                $state = null;
                            }
                            ?>
                        <tr>
                            <td>{{ $chronos->toLocalFormat($history->getCreated(), 'Y/m/d H:i:s') }}</td>
                            <td>
                                @if ($history->getStateText())
                                    <span class="badge p-2 c-order-state"
                                        style="{{ $state?->getColorCSS() ?? 'background-color: var(--bs-dark)' }}">
                                        {{ $history->getStateText() }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                {{ $history->getMessage() }}
                            </td>
                            {{--                            <td>--}}
                            {{--                                @if ($history->isNotify())--}}
                            {{--                                    <i class="fa fa-envelope"></i>--}}
                            {{--                                @endif--}}
                            {{--                            </td>--}}
                            <td>
                                @if ($history->getType() === OrderHistoryType::ADMIN())
                                    管理員
                                @elseif ($history->getType() === OrderHistoryType::SYSTEM())
                                    系統
                                @else
                                    您
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <form action="{{ $nav->to('order_item', ['no' => $item->getNo()]) }}" method="post">
            <div class="l-order-note mt-5">
                <div class="l-order-note__title mb-3">
                    <h4>訂單備註</h4>
                </div>

                <div class="l-order-note__content">
                    <textarea name="item[note]" class="form-control" rows="5"
                        required
                        @attr('disabled', $item->getDoneAt() !== null)
                    ></textarea>
                </div>

                <input name="item[no]" type="hidden" value="{{ $item->getNo() }}" />
                @formToken
            </div>

            <div class="text-center mt-4">
                <button class="btn btn-primary w-100">確定送出</button>
            </div>

            <div class="d-none">
                <input name="no" type="hidden" value="{{ $item->getNo() }}" />
                <x-csrf></x-csrf>
            </div>
        </form>
    </div>
@stop

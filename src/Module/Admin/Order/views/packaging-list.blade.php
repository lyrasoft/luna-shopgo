<?php

declare(strict_types=1);

namespace App\view;

/**
 * Global variables
 * --------------------------------------------------------------
 * @var $app       AppContext      Application context.
 * @var $vm        object          The view model object.
 * @var $uri       SystemUri       System Uri information.
 * @var $chronos   ChronosService  The chronos datetime service.
 * @var $nav       Navigator       Navigator object to build route.
 * @var $asset     AssetService    The Asset manage service.
 * @var $lang      LangService     The language translation service.
 */

use Lyrasoft\ShopGo\Entity\Order;
use Lyrasoft\ShopGo\Entity\OrderItem;
use Lyrasoft\ShopGo\ShopGoPackage;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;
use Windwalker\ORM\ORM;

use function Windwalker\value;

/**
 * @var $orders    Order[]
 * @var $orderItem OrderItem
 * @var $attachment OrderItem
 */

$orm = $app->service(ORM::class);

$shopGo = $app->service(ShopGoPackage::class);
$logo = value($shopGo->config('shop.logo'));
$siteName = value($shopGo->config('shop.sitename'));
?>

@extends('admin.global.pure')

@section('body')
    <style>
        .h-page-break {
          page-break-after: always;
        }

        .c-order-table thead tr > th {
          border-top: 2px solid #333;
          border-bottom: 2px solid #333;
          font-weight: bolder;
        }

        @media print {
          .ww-debug-console {
            display: none !important;
          }
        }
    </style>
    <div class="l-packaging-list container-fluid">
        <div class="l-packaging-list__toolbar card mb-5 d-print-none">
            <div class="card-body d-flex justify-content-between">
                <div></div>
                <div>
                    <button type="button" class="btn btn-primary btn-sm"
                        onclick="window.print();">
                        <i class="fa fa-print"></i>
                        @lang('shopgo.button.print')
                    </button>
                </div>
            </div>
        </div>

        @foreach ($orders as $order)
                <?php
                $orderItems = $orm->findList(
                    OrderItem::class,
                    [
                        'order_id' => $order->getId(),
                    ]
                )
                    ->all();

                [$orderItems, $attachments] = $orderItems->partition(
                    fn(OrderItem $orderItem) => $orderItem->getParentId() === 0
                );

                $attachments = $attachments->groupBy('parentId');
                ?>
            <div class="l-packaging-list__item mb-4">
                <table class="table table-borderless">
                    <tbody>
                    <tr>
                        <td valign="middle">
                            <img src="{{ $logo }}" alt="{{ $siteName }}" style="width: 300px">
                        </td>
                        <td class="" style="width: 40%">
                            <div>
                                <strong>@lang('shopgo.order.field.no'):</strong> #{{ $order->getNo() }}
                            </div>
                            <div>
                                <strong>@lang('shopgo.order.field.shipment.no'):</strong> {{ $order->getShippingInfo()->getShipmentNo() }}
                            </div>
{{--                            <div>--}}
{{--                                <strong>@lang('shopgo.order.field.payment'):</strong> {{ $order->getPayment()?->getTitle() }}--}}
{{--                            </div>--}}
                            <div>
                                <strong>@lang('shopgo.order.field.shipping'):</strong> {{ $order->getShipping()?->getTitle() }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">

                            <table class="c-order-table table">
                                <thead>
                                <th>
                                    @lang('shopgo.order.item.field.product')
                                </th>
                                <th style="width: 15%">
                                    @lang('shopgo.order.item.field.model')
                                </th>
                                <th class="text-end">
                                    @lang('shopgo.order.item.field.quantity')
                                </th>
                                </thead>
                                <tbody>
                                @foreach ($orderItems as $orderItem)

                                    <tr>
                                        <td>
                                            <div>
                                                {{ $orderItem->getTitle() }}
                                            </div>
                                            @if ($orderItem->getVariantHash())
                                                <div>
                                                    {{ $orderItem->getVariantTitle() }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-nowrap">
                                            {{ $orderItem->getProductData()['product']['model'] ?? '' }}
                                        </td>
                                        <td class="text-end">
                                            {{ $orderItem->getQuantity() }}
                                        </td>
                                    </tr>

                                    @foreach ($attachments as $pattachment)
                                        <tr>
                                            <td>
                                                <div>
                                                    {{ $pattachment->getTitle() }}
                                                </div>
                                                @if ($pattachment->getVariantHash())
                                                    <div>
                                                        {{ $pattachment->getVariantTitle() }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="text-nowrap">
                                                {{ $pattachment->getProductData()['product']['model'] ?? '' }}
                                            </td>
                                            <td class="text-end">
                                                {{ $pattachment->getQuantity() }}
                                            </td>
                                        </tr>
                                    @endforeach

                                @endforeach

                                </tbody>
                            </table>

                        </td>
                    </tr>
                    </tbody>
                </table>

                <div class="h-page-break"></div>
            </div>
        @endforeach
    </div>
@stop

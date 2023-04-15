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

use Lyrasoft\ShopGo\Cart\CartData;
use Lyrasoft\ShopGo\Cart\Price\PriceObject;
use Lyrasoft\ShopGo\Cart\Price\PriceSet;
use Lyrasoft\ShopGo\Entity\Order;
use Lyrasoft\ShopGo\Entity\OrderItem;
use Lyrasoft\ShopGo\Entity\OrderState;
use Lyrasoft\ShopGo\Entity\OrderTotal;
use Lyrasoft\ShopGo\ShopGoPackage;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;
use Windwalker\ORM\ORM;

/**
 * @var $item     Order
 * @var $cartData CartData
 */
$item = $order;
$orm = $app->service(ORM::class);
$shopGo = $app->service(ShopGoPackage::class);

// Totals
$totals = $cartData->getTotals();

$orderItems = $orm->findList(OrderItem::class, ['order_id' => $item->getId()])->all();

[$orderItems, $attachments] = $orderItems->partition(
    fn(OrderItem $orderItem) => $orderItem->getParentId() === 0
);

$attachments = $attachments->groupBy('parentId');

$paymentData = $item->getPaymentData();

?>

@extends('mail.mail-layout')

@section('content')
    <style>
      dl dt {
        min-width: 90px;
        font-weight: bold;
        margin-bottom: 5px;
        margin-right: 10px;
        float: left;
        clear: both;
      }

      dl dd {
        margin-left: 0;
        margin-bottom: 5px;
        float: left;
        content: " ";
      }

      .clearfix {
        clear: both;
      }

      th {
        text-align: left;
      }

      .text-end, .text-end th {
        text-align: right !important;
      }
    </style>

    @if ($isAdmin)
        <p>
            @lang('shopgo.mail.new.order.hi.admin')
        </p>

        <p>
            @lang('shopgo.mail.new.order.intro.admin')
        </p>
    @else
        <p>
            @lang('shopgo.mail.new.order.hi.buyer', name: $paymentData->getName())
        </p>

        <p>
            @lang('shopgo.mail.new.order.intro.buyer', sitename: $shopGo->config('shop.sitename'))
        </p>
    @endif

    <table style="width: 100%; margin-top: 40px">
        <thead>
        @if ($isAdmin)
            <tr>
                <td style="width: 50%">
                    <x-order-info.col1 :order="$item"></x-order-info.col1>
                </td>
                <td style="width: 50%">
                    <x-order-info.col2 :order="$item"></x-order-info.col2>
                </td>
            </tr>

            <tr>
                <td colspan="3">
                    <div style="margin-bottom: 30px">
                        <x-order-info.payment-data :order="$item"></x-order-info.payment-data>
                        <div class="clearfix"></div>
                    </div>
                    <div style="margin-bottom: 30px">
                        <x-order-info.shipping-data :order="$item"></x-order-info.shipping-data>
                        <div class="clearfix"></div>
                    </div>
                </td>
            </tr>
        @endif

        <tr>
            <td colspan="3">
                {{-- Order Items--}}
                <x-order-info.order-items
                    :order="$item"
                    :order-items="$orderItems"
                    :attachments="$attachments"
                    :totals="$totals"
                    :simple="true"
                ></x-order-info.order-items>
            </td>
        </tr>
        </thead>
    </table>

    <div style="margin-top: 40px">
        @if ($isAdmin)
            <a href="{{ $nav->to('admin::order_edit')->id($item->getId())->full() }}"
                class="btn btn-primary"
                target="_blank"
                style="width: 100%"
            >
                @lang('shopgo.mail.new.order.button.go.manage')
            </a>
        @else
            <a href="{{ $nav->to('front::my_order_item')->var('no', $item->getId())->full() }}"
                class="btn btn-primary"
                target="_blank"
                style="width: 100%"
            >
                @lang('shopgo.mail.new.order.button.see.order')
            </a>
        @endif
    </div>
@stop

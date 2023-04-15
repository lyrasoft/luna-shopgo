<?php

declare(strict_types=1);

namespace App\View;

/**
 * Global variables
 * --------------------------------------------------------------
 * @var  $app       AppContext      Application context.
 * @var  $vm        \Lyrasoft\ShopGo\Module\Admin\Invoice\InvoiceView  The view model object.
 * @var  $uri       SystemUri       System Uri information.
 * @var  $chronos   ChronosService  The chronos datetime service.
 * @var  $nav       Navigator       Navigator object to build route.
 * @var  $asset     AssetService    The Asset manage service.
 * @var  $lang      LangService     The language translation service.
 */

use Lyrasoft\ShopGo\Cart\Price\PriceSet;
use Lyrasoft\ShopGo\Entity\Order;
use Lyrasoft\ShopGo\Entity\OrderItem;
use Lyrasoft\ShopGo\Module\Admin\Invoice\InvoiceView;
use Lyrasoft\ShopGo\Service\CurrencyService;
use Lyrasoft\ShopGo\ShopGoPackage;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;

use function Windwalker\value;

/**
 * @var $order       Order
 * @var $orderItems  OrderItem[]
 * @var $attachments OrderItem[]
 * @var $totals      PriceSet
 */

$shopGo = $app->service(ShopGoPackage::class);
$logo = value($shopGo->config('shop.logo'));
$siteName = value($shopGo->config('shop.sitename'));

$currency = $app->service(CurrencyService::class);
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice-{{ $order->getInvoiceNo() }}</title>

    <style>
      html, body {
        font-size: 14px;
        font-family: {{ $shopGo->config('mpdf.font_family') ?: 'sans-serif' }};
      }

      h1, h2, h3, h4, h5, h6 {
        margin-top: 0;
        margin-bottom: .5em;
      }

      p {
        margin-top: 0;
        margin-bottom: .5rem;
      }

      .c-info {
        margin-bottom: .25rem;
      }

      table {
        width: 100%;
        border-spacing: 0;
      }

      .c-order-table th, .c-order-table td {
        padding: .5rem;
      }

      .c-order-table thead tr th {
        border-top: 3px solid #333;
        border-bottom: 3px solid #333;
        font-weight: bolder;
      }

      .c-order-table__item td {
        padding-top: 1rem;
        padding-bottom: 1rem;
      }

      .l-totals-table td {
        padding: .25rem;
      }

      .l-totals-table {
        border-top: 3px solid #333;
      }

      .text-nowrap {
        white-space: nowrap;
      }

      .text-end {
        text-align: right;
      }
    </style>
</head>
<body>
<div class="l-invoice">
    <div class="l-invoice__inner" style="max-width: 960px; margin-left: auto; margin-right: auto;">

        <header>
            <table class="l-header-table">
                <tbody>
                <tr>
                    <td class="l-header-table__logo" style="width: 50%">
                        <img src="{{ $uri->path($logo) }}" alt="{{ $siteName }}"
                            style="max-width: 350px; max-height: 70px">
                    </td>
                    <td class="l-header-table__info" style="text-align: right; font-size: .875rem">
                        <div>
                            <div class="c-info">
                            <span class="c-info__label">
                                @lang('shopgo.order.field.invoice.no') /
                            </span>
                                <strong>
                                    #{{ $order->getInvoiceNo() }}
                                </strong>
                            </div>
                            <div class="c-info">
                            <span class="c-info__label">
                                @lang('shopgo.order.field.no') /
                            </span>
                                <strong>
                                    #{{ $order->getNo() }}
                                </strong>
                            </div>
                            <div class="c-info">
                            <span class="c-info__label">
                                @lang('shopgo.order.field.order.time') /
                            </span>
                                <strong>
                                    {{ $chronos->toLocalFormat($order->getCreated(), 'Y-m-d') }}
                                </strong>
                            </div>
                            <div class="c-info">
                            <span class="c-info__label">
                                @lang('shopgo.order.field.payment') /
                            </span>
                                <strong>
                                    {{ $order->getPaymentData()->getPaymentTitle() }}
                                </strong>
                            </div>
                            <div class="c-info">
                            <span class="c-info__label">
                                @lang('shopgo.order.field.shipping') /
                            </span>
                                <strong>
                                    {{ $order->getShippingData()?->getShippingTitle() }}
                                </strong>
                            </div>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </header>

        <section style="margin-top: 40px">
            <table class="l-address-table">
                <thead>
                <tr>
                    <td class="l-address-table__payment">
                        <h4>
                            @lang('shopgo.order.payment.data.title')
                        </h4>
                        <div>
                            {!! nl2br($order->getPaymentData()->getFormatted()) !!}
                        </div>
                    </td>
                    <td class="l-address-table__shipping">
                        <h4>
                            @lang('shopgo.order.shipping.data.title')
                        </h4>
                        <div>
                            {!! nl2br($order->getShippingData()->getFormatted()) !!}
                        </div>
                    </td>
                </tr>
                </thead>
            </table>
        </section>

        <section style="margin-top: 40px">
            <table class="c-order-table table">
                <thead>
                <tr>
                    <th>
                        @lang('shopgo.order.item.field.product')
                    </th>
                    <th style="width: 15%; text-align: right">
                        @lang('shopgo.order.item.field.quantity')
                    </th>
                    <th style="width: 15%; text-align: right">
                        @lang('shopgo.order.item.field.price.unit')
                    </th>
                    <th style="width: 20%; text-align: right">
                        @lang('shopgo.order.item.field.total')
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach ($orderItems as $orderItem)

                    <tr class="c-order-table__item c-order-table__item--item">
                        <td>
                            <div>
                                {{ $orderItem->getTitle() }}
                            </div>
                            @if ($orderItem->getVariantHash())
                                <div style="font-size: .875rem; color: #666; margin-top: .25rem">
                                    {{ $orderItem->getVariantTitle() }}
                                </div>
                            @endif
                        </td>
                        <td class="text-nowrap text-end">
                            {{ $orderItem->getQuantity() }}
                        </td>
                        <td class="text-nowrap text-end">
                            {{ $currency->format($orderItem->getPriceUnit()) }}
                        </td>
                        <td class="text-end text-end">
                            {{ $currency->formatWithCode($orderItem->getTotal()) }}
                        </td>
                    </tr>

                    @foreach ($attachments as $attachment)
                        <tr class="c-order-table__item c-order-table__item--attachment">
                            <td>
                                <div style="padding-left: .5rem">
                                    <div>
                                        {{ $attachment->getTitle() }}
                                    </div>
                                    @if ($attachment->getVariantHash())
                                        <div style="font-size: .875rem; color: #666; margin-top: .25rem">
                                            {{ $attachment->getVariantTitle() }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="text-nowrap">
                                {{ $attachment->getProductData()['product']['model'] ?? '' }}
                            </td>
                            <td class="text-end">
                                {{ $attachment->getQuantity() }}
                            </td>
                        </tr>
                    @endforeach

                @endforeach

                </tbody>
            </table>

            <div class="text-end">
                    <?php
                $total = $totals->remove('total');
                $grandTotal = $totals->remove('grand_total');
                ?>
                <table class="l-totals-table" style="text-align: right;">
                    <tbody>
                    <tr>
                        <th style="text-align: right">
                            <div>
                                {{ $total->getLabel() }}
                            </div>
                        </th>
                        <td style="vertical-align: middle; width: 150px;">
                            {{ $currency->format($total) }}
                        </td>
                    </tr>

                    @foreach ($totals as $total)
                        <tr data-type="{{ $total->getParamValue('type') }}"
                            data-discount-id="{{ $total->getParamValue('id') }}">
                            <th style="text-align: right">
                                <div>
                                    {{ $total->getLabel() }}
                                </div>
                                @if (str_starts_with($total->getName(), 'discount:'))
                                    <div class="small">
                                        {{ $total->getParamValue('title') }}
                                    </div>
                                @endif
                            </th>
                            <td style="; vertical-align: middle" width="150px">
                                {{ $currency->format($total) }}
                            </td>
                        </tr>
                    @endforeach

                    <tr>
                        <th style="text-align: right">
                            <div>
                                {{ $grandTotal->getLabel() }}
                            </div>
                        </th>
                        <td class="" style="font-weight: bold; vertical-align: middle; width: 150px;">
                            {{ $currency->formatWithCode($grandTotal) }}
                        </td>
                    </tr>
                    </tbody>
                </table>

            </div>
        </section>
    </div>

    <footer style="margin-top: 40px; text-align: center;">
        <p>
            {{ $siteName }}
        </p>
        <p style="font-size: .9375rem">
            <a href="{{ $uri->root() }}" target="_blank"
                style="color: #333; text-decoration: none;">
                {{ $uri->root() }}
            </a>
        </p>
    </footer>
</div>
</body>
</html>

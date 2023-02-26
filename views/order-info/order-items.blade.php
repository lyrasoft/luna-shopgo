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

use Lyrasoft\ShopGo\Cart\Price\PriceObject;
use Lyrasoft\ShopGo\Cart\Price\PriceSet;
use Lyrasoft\ShopGo\Entity\OrderItem;
use Lyrasoft\ShopGo\Service\CurrencyService;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;

/**
 * @var $orderItems OrderItem[]
 * @var $totals     PriceSet|PriceObject[]
 * @var $total      PriceObject
 * @var $grandTotal PriceObject
 */

$currency = $app->service(CurrencyService::class);
?>

<div class="l-order-items table-responsive mb-5">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th style="width: 5%">
                @lang('unicorn.field.id')
            </th>
            <th style="width: 5%">
                @lang('shopgo.order.item.field.image')
            </th>
            <th>
                @lang('shopgo.order.item.field.product')
            </th>
            <th>
                @lang('shopgo.order.item.field.price.unit')
            </th>
            <th>
                @lang('shopgo.order.item.field.quantity')
            </th>
            <th>
                @lang('shopgo.order.item.field.total')
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach($orderItems as $orderItem)
            <tr>
                <td>{{ $orderItem->getId() }}</td>
                <td>
                    <img src="{{ $orderItem->getImage() }}" class="me-3" width="100"
                        alt="Image">
                </td>
                <td>
                    <h4>
                        {{ $orderItem->getTitle() }}
                    </h4>
                    @if ($orderItem->getVariantHash())
                        <div>
                            {{ $orderItem->getVariantTitle() }}
                        </div>
                    @endif
                </td>
                <td class="text-end" style="width: 200px">
                    {{ $currency->format($orderItem->getPriceUnit()) }}
                </td>
                <td style="width: 200px">{{ $orderItem->getQuantity() }}</td>
                <td class="text-end" style="width: 200px">
                    {{ $currency->formatWithCode($orderItem->getTotal()) }}
                </td>
            </tr>

            @if ($attachmentItems = $attachments[$orderItem->getId()] ?? [])
                @foreach ($attachmentItems as $atachment)
                    <tr class="bg-light">
                        <td>{{ $atachment->getId() }}</td>
                        <td>
                            <img src="{{ $atachment->getImage() }}" class="me-3" width="75"
                                alt="Image">
                        </td>
                        <td>
                            <h5>
                                {{ $atachment->getTitle() }}
                            </h5>
                            @if ($atachment->getVariantHash())
                                <div>
                                    {{ $atachment->getVariantTitle() }}
                                </div>
                            @endif

                            <div>
                                <span class="badge bg-secondary">
                                    @lang('shopgo.order.item.label.attachment')
                                </span>
                            </div>
                        </td>
                        <td class="text-end" style="width: 200px">
                            {{ $currency->format($atachment->getPriceUnit()) }}
                        </td>
                        <td style="width: 200px">{{ $atachment->getQuantity() }}</td>
                        <td class="text-end" style="width: 200px">
                            {{ $currency->formatWithCode($atachment->getTotal()) }}
                        </td>
                    </tr>
                @endforeach

                <tr class="bg-light fs-5 fw-bold">
                    <td colspan="5" class="text-end">
                        @lang('shopgo.order.item.attached.final.total')
                    </td>
                    <td class="text-end">
                        {{ $currency->formatWithCode($orderItem->getPriceSet()['attached_final_total']) }}
                    </td>
                </tr>
            @endif
        @endforeach
        </tbody>
    </table>

    <div class="text-end">
        <?php
        $total = $totals->remove('total');
        $grandTotal = $totals->remove('grand_total');
        ?>
        <table class="table fs-5">
            <tr>
                <th style="border: none">
                    <div>
                        {{ $total->getLabel() }}
                    </div>
                </th>
                <td style="border: none; vertical-align: middle; width: 150px;">
                    {{ $currency->format($total) }}
                </td>
            </tr>

            @foreach ($totals as $total)
                <tr data-type="{{ $total->getParamValue('type') }}"
                    data-discount-id="{{ $total->getParamValue('id') }}">
                    <th style="border: none">
                        <div>
                            {{ $total->getLabel() }}
                        </div>
                        @if (str_starts_with($total->getName(), 'discount:'))
                        <div class="small">
                            {{ $total->getParamValue('title') }}
                        </div>
                        @endif
                    </th>
                    <td style="border: none; vertical-align: middle" width="150px">
                        {{ $currency->format($total) }}
                    </td>
                </tr>
            @endforeach

            <tr>
                <th style="border: none">
                    <div>
                        {{ $grandTotal->getLabel() }}
                    </div>
                </th>
                <td class="fw-bold" style="border: none; vertical-align: middle; width: 150px;">
                    {{ $currency->formatWithCode($grandTotal) }}
                </td>
            </tr>
        </table>

    </div>
</div>

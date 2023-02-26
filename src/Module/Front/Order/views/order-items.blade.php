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

<div class="l-order-items mb-5">
    @foreach($orderItems as $orderItem)
        <div class="card c-order-item">
            <div class="card-body d-grid d-lg-flex gap-3">
                <div class="d-flex gap-3 me-auto">
                    <div class="c-order-item__image">
                        <img src="{{ $orderItem->getImage() }}" class="me-3" width="100"
                            alt="Image">
                    </div>

                    <div class="c-order-item__content">
                        <h4>
                            {{ $orderItem->getTitle() }}
                        </h4>
                        @if ($orderItem->getVariantHash())
                            <div>
                                {{ $orderItem->getVariantTitle() }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="d-flex gap-3 justify-content-end">
                    <div class="text-end" style="">
                        {{ $currency->format($orderItem->getPriceUnit()) }}
                    </div>
                    <div style="">
                        x{{ $orderItem->getQuantity() }}
                    </div>
                    <div class="text-end" style="">
                        =
                        {{ $currency->formatWithCode($orderItem->getTotal()) }}
                    </div>
                </div>
            </div>

            @if ($attachmentItems = $attachments[$orderItem->getId()] ?? [])
                <div class="card-footer">
                    @foreach ($attachmentItems as $attachment)
                        <div class="d-grid d-lg-flex gap-3 py-2 border-bottom">
                            <div class="d-flex gap-3 me-auto">
                                <div class="c-order-item__image">
                                    <img src="{{ $attachment->getImage() }}" class="me-3" width="75"
                                        alt="Image">
                                </div>

                                <div class="c-order-item__content">
                                    <h5>
                                        {{ $attachment->getTitle() }}
                                    </h5>
                                    @if ($attachment->getVariantHash())
                                        <div>
                                            {{ $attachment->getVariantTitle() }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="d-flex gap-3 justify-content-end">
                                <div class="text-end" style="">
                                    {{ $currency->format($attachment->getPriceUnit()) }}
                                </div>
                                <div style="">
                                    x{{ $attachment->getQuantity() }}
                                </div>
                                <div class="text-end" style="">
                                    =
                                    {{ $currency->formatWithCode($attachment->getTotal()) }}
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="fs-5 fw-bold mt-3 d-flex justify-content-end gap-3">
                        <div class="text-end">
                            @lang('shopgo.order.item.attached.final.total')
                        </div>
                        <div class="text-end">
                            {{ $currency->formatWithCode($orderItem->getPriceSet()['attached_final_total']) }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endforeach

    <div class="text-end mt-3">
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

            <tr class="border-top">
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

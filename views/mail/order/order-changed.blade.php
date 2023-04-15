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
use Lyrasoft\ShopGo\Entity\OrderHistory;
use Lyrasoft\ShopGo\Entity\OrderState;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;

use function Windwalker\str;

/**
 * @var $order   Order
 * @var $state   ?OrderState
 * @var $history OrderHistory
 */

$paymentData = $order->getPaymentData();

?>

@extends('mail.mail-layout')

@section('content')
    @if (!$isAdmin)
        <p>@lang('shopgo.order.mail.changed.hi.member', name: $paymentData->getName())</p>
    @else
        <p>@lang('shopgo.order.mail.changed.hi.admin')</p>
    @endif

    @if ($state)
        <p>
            @lang('shopgo.order.mail.changed.to'):
            <span
                style="padding: .25em .5em; {{ $state->getColorCSS() }}; color: {{ $state->getContrastColor() }}; border-radius: 3px;">
                {{ $state->getTitle() }}
            </span>
        </p>
    @endif

    @if ($history->getMessage())
        <p>
            <strong>@lang('shopgo.order.mail.changed.comment')</strong>
        </p>
        <blockquote style="margin: 0; padding: .5em 1em;background-color: #eee">
            {!! html_escape($history->getMessage()) !!}
        </blockquote>
    @endif

    <div style="margin-top: 40px">
        @if ($isAdmin)
            <a href="{{ $nav->to('admin::order_edit')->id($order->getId())->full() }}"
                class="btn btn-primary"
                target="_blank"
                style="width: 100%"
            >
                @lang('shopgo.mail.new.order.button.go.manage')
            </a>
        @else
            <a href="{{ $nav->to('front::my_order_item')->var('no', $order->getNo())->full() }}"
                class="btn btn-primary"
                target="_blank"
                style="width: 100%"
            >
                @lang('shopgo.mail.new.order.button.see.order')
            </a>
        @endif
    </div>
@stop

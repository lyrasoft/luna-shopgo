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

use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;

?>

@extends('global.body')


@section('body')
    <div class="container">
        <div class="d-flex my-5">
            <div class="card mx-auto" style="min-width: 450px">
                <div class="card-body text-center p-4">
                    <h3>@lang('shopgo.checkout.text.order.created')</h3>

                    <div class="fw-bold fs-5">
                        #{{ $order->getNo() }}
                    </div>

                    <div class="mt-4">
                        <a href="{{ $nav->to('my_order_item')->var('no', $order->getNo()) }}"
                            class="btn btn-primary w-100">
                            @lang('shopgo.cart.text.to.my.order')
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

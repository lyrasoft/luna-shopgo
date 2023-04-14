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
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;

/**
 * @var Order $order
 */

?>

<div class="card">
    <div class="card-body">
        <dl class="row p-2 mb-0">
            <dt class="col-4">
                @lang('shopgo.order.field.payment.no')
            </dt>
            <dd class="col-8">
                {{ $order->getPaymentNo() ?: '-' }}
            </dd>

            <dt class="col-4">
                @lang('shopgo.order.field.shipping.no')
            </dt>
            <dd class="col-8">
                {{ $order->getShippingNo() ?: '-' }}
            </dd>

            <dt class="col-4">
                @lang('shopgo.order.field.invoice.no')
            </dt>
            <dd class="col-8">
                <div class="d-flex">
                    <div>
                        @if ($order->getInvoiceNo())
                            #{{ $order->getInvoiceNo() }}
                        @else
                            -
                        @endif
                    </div>
                    <div class="ms-auto">
                        {!! $invoiceControl ?? '' !!}
                    </div>
                </div>
            </dd>
        </dl>
    </div>
</div>

<?php

declare(strict_types=1);

namespace App\View;

/**
 * Global variables
 * --------------------------------------------------------------
 * @var  $app       AppContext      Application context.
 * @var  $view      \Lyrasoft\ShopGo\Module\Admin\Order\OrderEditView  The view modal object.
 * @var  $uri       SystemUri       System Uri information.
 * @var  $chronos   ChronosService  The chronos datetime service.
 * @var  $nav       Navigator       Navigator object to build route.
 * @var  $asset     AssetService    The Asset manage service.
 * @var  $lang      LangService     The language translation service.
 */

use Lyrasoft\ShopGo\Entity\Order;
use Lyrasoft\ShopGo\Module\Admin\Order\OrderEditView;
use Lyrasoft\ShopGo\Shipping\ShipmentCreatingInterface;
use Lyrasoft\ShopGo\Shipping\ShipmentPrintableInterface;
use Lyrasoft\ShopGo\Shipping\ShippingService;
use Lyrasoft\ShopGo\Shipping\ShippingStatusInterface;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;

/**
 * @var $item Order
 */

$shippingService = $app->service(ShippingService::class);
$typeInstance = $shippingService->createTypeInstance($item->getShipping());

$hasCreatShipment = $typeInstance instanceof ShipmentCreatingInterface;
$hasUpdateShipping = $typeInstance instanceof ShipmentCreatingInterface;

?>

<div x-title="toolbar" x-data="{ form: $store.form }" class="l-toolbar">
    {{-- Save --}}
    {{--    <button type="button" class="btn btn-success btn-sm uni-btn-save"--}}
    {{--        data-task="save"--}}
    {{--        @click="form.post();"--}}
    {{--        style="width: 150px"--}}
    {{--    >--}}
    {{--        <span class="fa fa-save"></span>--}}
    {{--        @lang('unicorn.toolbar.save')--}}
    {{--    </button>--}}

    {{--    --}}{{-- Save2Close --}}
    {{--    <button type="button" class="btn btn-primary btn-sm uni-btn-save2close"--}}
    {{--        data-task="save"--}}
    {{--        @click="form.post(null, { task: 'save2close' });">--}}
    {{--        <span class="fa fa-check"></span>--}}
    {{--        @lang('unicorn.toolbar.save2close')--}}
    {{--    </button>--}}

    @if ($hasCreatShipment || $hasUpdateShipping)
        <div class="dropdown d-inline-block c-toolbar-shipping">
            <button class="btn btn-dark btn-sm dropdown-toggle"
                data-bs-toggle="dropdown">
                <i class="fa fa-truck"></i>
                @lang('shogo.order.toolbar.button.shipping.control')
            </button>
            <div class="dropdown-menu">
                @if ($typeInstance instanceof ShipmentCreatingInterface)
                    <button type="button"
                        class="dropdown-item"
                        data-task="create_shipments"
                        @attr('disabled', WINDWALKER_DEBUG || $item->getShippingNo() ?: null)
                        @click="form.patch('{{ $nav->to('order_list') }}', { task: 'create_shipments', id: '{{ $item->getId() }}' });"
                    >
                        <i class="fa fa-fw fa-truck-clock"></i>
                        @lang('shopgo.order.button.create.shipment')
                    </button>
                @endif

                @if ($typeInstance instanceof ShippingStatusInterface)
                    <button type="button"
                        class="dropdown-item"
                        data-task="update_shippings"
                        @click="form.patch('{{ $nav->to('order_list') }}', { task: 'update_shippings', id: '{{ $item->getId() }}' });"
                    >
                        <i class="fa fa-fw fa-truck-fast"></i>
                        @lang('shopgo.order.button.update.shipping.status')
                    </button>
                @endif
            </div>
        </div>
    @endif

    <div class="dropdown d-inline-block c-toolbar-print">
        <button class="btn btn-info btn-sm dropdown-toggle"
            data-bs-toggle="dropdown">
            <i class="fa fa-print"></i>
            @lang('shogo.order.toolbar.button.print.control')
        </button>
        <div class="dropdown-menu">
            <a class="dropdown-item"
                data-task="print_shipments"
                href="{{ $nav->to('order_list')->task('print_packaging')->id($item->getId()) }}"
                target="_blank"
            >
                <i class="fa fa-box-open"></i>
                @lang('shopgo.order.button.print.packaging.list')
            </a>

            @if ($typeInstance instanceof ShipmentPrintableInterface)
                <a class="dropdown-item"
                    data-task="print_shipments"
                    href="{{ $nav->to('order_list')->task('print_shipments')->id($item->getId()) }}"
                    target="_blank"
                >
                    <i class="fa fa-truck"></i>
                    @lang('shopgo.order.button.print.shipment')
                </a>
            @endif
        </div>
    </div>

    @if (!$item->getInvoiceNo())
        <button type="button"
            class="btn btn-success btn-sm"
            @click="form.post('{{ $nav->to('invoice') }}', { id: '{{ $item->getId() }}' })"
        >
            <i class="fa fa-file-invoice"></i>
            @lang('shopgo.order.button.invoice.create')
        </button>
    @endif

    {{-- Cancel --}}
    <a class="btn btn-default btn-outline-secondary btn-sm uni-btn-cancel"
        href="{{ $nav->to('order_list') }}">
        <span class="fa fa-times"></span>
        @lang('unicorn.toolbar.cancel')
    </a>
</div>

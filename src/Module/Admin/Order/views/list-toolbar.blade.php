<?php

declare(strict_types=1);

namespace App\View;

/**
 * Global variables
 * --------------------------------------------------------------
 * @var  $app       AppContext      Application context.
 * @var  $vm        \Lyrasoft\ShopGo\Module\Admin\Order\OrderListView  The view model object.
 * @var  $uri       SystemUri       System Uri information.
 * @var  $chronos   ChronosService  The chronos datetime service.
 * @var  $nav       Navigator       Navigator object to build route.
 * @var  $asset     AssetService    The Asset manage service.
 * @var  $lang      LangService     The language translation service.
 */

use Lyrasoft\ShopGo\Module\Admin\Order\OrderListView;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;
use Windwalker\Form\Form;

/**
* @var $form Form
*/

?>

<div x-title="toolbar" x-data="{ form: $store.grid.form, grid: $store.grid }" class="l-toolbar">

    @if ($form->getField('filter/order.shipping_id')?->getValue())
        {{-- Shipments --}}
        <button type="button" class="btn btn-warning btn-sm uni-btn-print"
            data-task="print_list"
            data-uri="{{ $nav->to('order_list')->task('print_shipments') }}"
        >
            <i class="fa fa-print"></i>
            @lang('shopgo.order.button.print.shipment')
        </button>
    @endif

    {{-- Packing --}}
    <button type="button" class="btn btn-info btn-sm uni-btn-print"
        data-task="print_list"
        data-uri="{{ $nav->to('order_list')->task('print_packaging') }}"
    >
        <i class="fa fa-box-open"></i>
        @lang('shopgo.order.button.print.packaging.list')
    </button>

    @if ($form?->countFields(null, 'batch'))
        {{-- Batch --}}
        <button type="button" class="btn btn-dark btn-sm uni-btn-batch"
            @click="grid.validateChecked(null, function () {
                (new bootstrap.Modal('#batch-modal')).show();
            })"
        >
            <i class="fa fa-sliders"></i>
            @lang('unicorn.toolbar.batch')
        </button>
    @endif

    {{-- Delete --}}
    @can('superuser')
        <button type="button" class="btn btn-outline-danger btn-sm uni-btn-delete"
            @click="grid.deleteList()"
        >
            <i class="fa fa-trash"></i>
            @lang('unicorn.toolbar.delete')
        </button>
    @endcan
</div>

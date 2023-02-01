<?php

declare(strict_types=1);

namespace App\View;

/**
 * Global variables
 * --------------------------------------------------------------
 * @var  $app       AppContext      Application context.
 * @var  $vm        \Lyrasoft\ShopGo\Module\Admin\OrderState\OrderStateListView  The view model object.
 * @var  $uri       SystemUri       System Uri information.
 * @var  $chronos   ChronosService  The chronos datetime service.
 * @var  $nav       Navigator       Navigator object to build route.
 * @var  $asset     AssetService    The Asset manage service.
 * @var  $lang      LangService     The language translation service.
 */

use Lyrasoft\ShopGo\Module\Admin\OrderState\OrderStateListView;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;

?>

<div x-title="toolbar" x-data="{ form: $store.grid.form, grid: $store.grid }" class="l-toolbar">
    {{-- Create --}}
    <a class="btn btn-primary btn-sm uni-btn-new"
        href="{{ $nav->to('order_state_edit')->var('new', 1) }}"
        style="min-width: 150px"
    >
        <i class="fa fa-plus"></i>
        @lang('unicorn.toolbar.new')
    </a>

    {{-- Duplicate --}}
    <button type="button" class="btn btn-info btn-sm uni-btn-duplicate"
        @click="grid.form.post()"
    >
        <i class="fa fa-clone"></i>
        @lang('unicorn.toolbar.duplicate')
    </button>

    {{-- Delete --}}
    <button type="button" class="btn btn-outline-danger btn-sm uni-btn-delete"
        @click="grid.deleteList()"
    >
        <i class="fa fa-trash"></i>
        @lang('unicorn.toolbar.delete')
    </button>
</div>

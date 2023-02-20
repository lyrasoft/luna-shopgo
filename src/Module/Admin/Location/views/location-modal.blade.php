<?php

declare(strict_types=1);

namespace App\View;

/**
 * Global variables
 * --------------------------------------------------------------
 * @var $app       AppContext      Application context.
 * @var $vm        \Lyraoft\ShopGo\Module\Admin\Location\LocationListView  The view model object.
 * @var $uri       SystemUri       System Uri information.
 * @var $chronos   ChronosService  The chronos datetime service.
 * @var $nav       Navigator       Navigator object to build route.
 * @var $asset     AssetService    The Asset manage service.
 * @var $lang      LangService     The language translation service.
 */

use Lyraoft\ShopGo\Module\Admin\Location\LocationListView;
use Lyrasoft\ShopGo\Entity\Location;
use Unicorn\Html\Breadcrumb;
use Unicorn\Workflow\BasicStateWorkflow;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;

/**
 * @var $parents Location[]
 */

$callback = $app->input('callback');
$workflow = $app->service(BasicStateWorkflow::class);

$breadcrumb = $app->service(Breadcrumb::class);
$breadcrumb->push(
    $lang('shopgo.location.root'),
    $nav->self()->var('current_id', 0)
);

$parents->shift();
$parentsCount = count($parents);

$path = [];

foreach ($parents as $i => $parent) {
    $path[] = $parent->getTitle();

    $breadcrumb->push(
        $parent->getTitle(),
        ($i + 1) !== $parentsCount
            ? $nav->self()->var('current_id', $parent->getId())
            : null
    );
}
?>

@extends('admin.global.pure')

@section('body')
    <form id="admin-form" action="" x-data="{ grid: $store.grid }"
        x-ref="gridForm"
        data-ordering="{{ $ordering }}"
        method="post">

        <x-filter-bar :form="$form" :open="$showFilters">
            <x-slot name="end">
                @if (!$current->isRoot())
                    <div class="d-flex gap-3">
                        <a href="{{ $nav->self()->var('current_id', $current->getParentId()) }}"
                            class="btn btn-outline-primary btn-sm">
                            <i class="fa fa-chevron-left"></i>
                            @lang('shopgo.location.button.back')
                        </a>
                        {!! $breadcrumb->render() !!}
                    </div>
                @endif
            </x-slot>
        </x-filter-bar>

        <div>
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th style="width: 10%">
                        <x-sort field="location.state">
                            @lang('unicorn.field.state')
                        </x-sort>
                    </th>
                    {{-- Type --}}
                    <th style="width: 5%" class="text-nowrap">
                        @lang('unicorn.field.type')
                    </th>
                    <th>
                        <x-sort field="location.title">
                            @lang('unicorn.field.title')
                        </x-sort>
                    </th>
                    {{-- Native --}}
                    <th class="text-nowrap">
                        <x-sort field="location.native">
                            @lang('shopgo.location.field.native')
                        </x-sort>
                    </th>

                    {{-- Code --}}
                    <th class="text-nowrap">
                        <x-sort field="location.code">
                            @lang('shopgo.location.field.code')
                        </x-sort>
                    </th>

                    <th></th>
                    <th class="text-end text-nowrap" style="width: 1%">
                        <x-sort field="location.id">
                            @lang('unicorn.field.id')
                        </x-sort>
                    </th>
                </tr>
                </thead>

                <tbody>
                @foreach($items as $i => $item)
                    @php($data = [
                        'title' => $item->title,
                        'value' => $item->id,
                        'image' => $item->image,
                        'path' => $path
                    ])
                        <?php $entity = $vm->prepareItem($item); ?>
                    <tr>
                        <td>
                            <x-state-dropdown color-on="text"
                                button-style="width: 100%"
                                use-states
                                readonly
                                :workflow="$workflow"
                                :id="$item->id"
                                :value="$item->state"
                            ></x-state-dropdown>
                        </td>
                        {{-- Type --}}
                        <td>
                            {{ $entity->getType()->getTitle($lang) }}
                        </td>
                        <td>
                            <a href="javascript://"
                                onclick="parent.{{ $callback }}({{ json_encode($data) }})">
                                {{ $item->title }}
                            </a>
                        </td>
                        <td>
                            <div class="text-secondary ms-2">
                                {{ $entity->getNative() ?: '-' }}
                            </div>
                        </td>

                        <td>
                                <?php
                                $codes = [$entity->getCode(), $entity->getCode3()];
                                echo implode(' / ', array_filter($codes));
                                ?>
                        </td>

                        <td style="width: 3%" class="text-nowrap">
                            @if ($entity->getRgt() - $entity->getLft() > 1)
                                <div class="ms-auto ml-auto">
                                    <a href="{{ $nav->self()->var('current_id', $entity->getId()) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="fa fa-list"></i>
                                        @lang('shopgo.location.see.children')
                                    </a>
                                </div>
                            @endif
                        </td>
                        <td class="text-end">
                            {{ $item->id }}
                        </td>
                    </tr>
                @endforeach
                </tbody>

                <tfoot>
                <tr>
                    <td colspan="20">
                        {!! $pagination->render() !!}
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>

        <div class="d-none">
            <input name="_method" type="hidden" value="PUT" />
            <x-csrf></x-csrf>
        </div>

        <x-batch-modal :form="$form" namespace="batch"></x-batch-modal>
    </form>

@stop

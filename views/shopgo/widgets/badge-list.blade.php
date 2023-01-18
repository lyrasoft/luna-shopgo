<?php

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

declare(strict_types=1);

namespace App\View;

use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;
use Windwalker\Utilities\TypeCast;

/**
 * @var $items     iterable<string>
 * @var $itemClass string
 * @var $max       int
 */

$max ??= 3;
$itemClass ??= 'badge bg-primary';
$emptyText ??= '';
$emptyClass ??= 'badge bg-secondary';

$items = TypeCast::toArray($items);
$isEmpty = count($items) === 0;
$moreItems = array_splice($items, $max);

?>

@if (!$isEmpty)
    @foreach ($items as $i => $item)
        <div>
            @if ($slot)
                {!! $slot(item: $item, i: $i) !!}
            @else
                <span class="{{ $itemClass }}">
                {{ $item }}
            </span>
            @endif
        </div>
    @endforeach

    @if ($moreItems !== [])
            <?php
            $moreText = $lang('shopgo.widget.badge.list.more');
            $tooltip = implode(', ', $moreItems);
            ?>
        <div class="d-inline-block" data-bs-toggle="tooltip" title="{{ $tooltip }}">
            @if ($slot)
                {!! $slot(item: $moreText, i: 9999) !!}
            @else
                <span class="{{ $itemClass }}">
                ({{ count($moreItems) }}) {{ $moreText }}
            </span>
            @endif
        </div>
    @endif
@elseif ((string) $emptyText !== '')
    @if ($slot)
        {!! $slot(item: $emptyText, i: -1) !!}
    @else
        <span class="{{ $emptyClass }}">
            {{ $emptyText }}
        </span>
    @endif
@endif


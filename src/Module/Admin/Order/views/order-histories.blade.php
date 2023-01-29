<?php

declare(strict_types=1);

namespace App\View;

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

use App\Entity\OrderHistory;
use App\Enum\OrderHistoryType;
use App\Service\OrderStateService;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;

/**
 * @var OrderHistory $history
 */

$states = $app->service(OrderStateService::class)->getOrderStates()->groupBy('id');
?>

{!! $slot ?? '' !!}

@foreach($histories as $history)
    <?php
        $state = $states[$history->getStateId()];
    ?>
    <div class="list-group-item order-history">
        <div class="order-history__info d-flex text-muted mb-2">
            <div class="order-history__info-item mr-2">
                <span class="fa fa-calendar"></span>
                {{ $chronos->toLocalFormat($history->getCreated()) }}
            </div>

            @if ($history->isNotify())
                <div class="order-history__info-item ms-2">
                    <span class="fa fa-envelope"
                        data-bs-toggle="tooltip"
                        title="發信通知使用者"></span>
                </div>
            @endif
        </div>
        <div class="order-history__title">
            {{ $history->getType()->trans($lang) }}

            @if (!$history->getType()->equals(OrderHistoryType::SYSTEM()))
                <a href="{{ $nav->to('user_edit', ['id' => $history->getCreatedBy()]) }}">
                    {{ $history->user->name }}
                </a>
            @endif

            @if ($history->getStateId())
                將此訂單改為
                <span class="badge bg-{{ $history->getState()->getColor() }}">
                    {{ $history->getState()->trans($lang) }}
                </span>
            @endif

            @if (trim($history->getMessage()) !== '')
                @if ($history->getState())
                    並留言:
                @else
                    留言:
                @endif
            @endif
        </div>

        @if (trim($history->getMessage()) !== '')
            <div class="order-history__message p-2 bg-light mt-2">
                {!! html_escape($history->getMessage(), true) !!}
            </div>
        @endif
    </div>
@endforeach

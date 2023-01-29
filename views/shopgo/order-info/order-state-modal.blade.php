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

use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;

/**
 * @var \App\Entity\Order $order
 */

$route ??= 'order_list'

$states = $app->service(\App\Service\OrderStateService::class)->getOrderStates();
?>

<div class="modal fade" id="order-state-modal-{{ $order->getId() }}" tabindex="-1" role="dialog"
    aria-labelledby="order-state-modal-label"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="order-state-modal-label">
                    調整狀態
                </h4>
                <button type="button" class="close btn-close" data-bs-dismiss="modal" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true" class="visually-hidden">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="state-form" method="post" action="{{ $nav->to($route) }}">
                    <div class="form-group mb-4">
                        <label for="input-order-state" class="form-label">狀態</label>
                        <select id="input-order-state" name="state" class="form-select"
                            @attr('disabled', !$canChange ? true : null)>
                            <option value="">- 不變更 -</option>
                            @foreach ($states as $state)
                                <option value="{{ $state->getValue() }}">
                                    {{ $state->getTitle() }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-4 form-check">
                        <input id="input-order-notify" type="checkbox" value="1" name="notify"
                        class="form-check-input" />
                        <label for="input-order-notify" class="form-label">
                            發送通知
                        </label>
                    </div>

                    <div class="form-group mb-4">
                        <label for="inout-order-message" class="form-label">備註</label>
                        <textarea name="message" id="inout-order-message" class="form-control" rows="7"></textarea>
                    </div>

                    <div>
                        <button type="submit" class="btn btn-primary w-100" data-dos>
                            送出
                        </button>
                    </div>

                    <div class="d-none">
                        <input name="_method" type="hidden" value="PATCH" />
                        <input name="task" type="hidden" value="transition" />
                        <input name="id" type="hidden" value="{{ $order->getId() }}" />
                        <x-csrf></x-csrf>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

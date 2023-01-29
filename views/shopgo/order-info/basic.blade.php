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

$vendor = $vendor ?? 0;

/**
 * @var \App\Entity\Order $item
 * @var \App\Entity\Order $parent
 */

?>

<div class="c-card">
    <div class="c-card__header">
        訂單資訊
    </div>

    <div class="c-card__body">
        <div class="row">
            <div class="col-md-6">
                <dl class="row mb-4">
                    <dt class="col-4 fw-normal">
                        訂單編號
                    </dt>
                    <dd class="col-8 fw-normal">
                        {{ $item->getNo() }}
                    </dd>
                </dl>
                <dl class="row mb-4">
                    <dt class="col-4 fw-normal">
                        訂單狀態
                    </dt>
                    <dd class="col-8 fw-normal">
                        {{ $item->getState()->trans($lang) }}
                    </dd>
                </dl>
                <dl class="row mb-0 mb-4 mb-md-0">
                    <dt class="col-4 fw-normal">
                        付款方式
                    </dt>
                    <dd class="col-8 fw-normal">
                        <div>
                            {{ $item->getPayment()->trans($lang)}}
                        </div>

                        @if(!$vendor && $item->getPayment()->equals(\App\Enum\OrderPayment::ATM()))
                        <div class="mt-3">
                            <?php $info = $item->getPaymentInfo(); ?>
                            @if ($info['vAccount'] ?? null)
                                <div class="text-danger">
                                    繳款帳號: ({{ $info['BankCode'] ?? '' }}) {{ $info['vAccount'] ?? '' }}
                                </div>
                            @endif
                            @if ($info['ExpireDate'] ?? null)
                                <?php
                                $expiry = \Windwalker\Core\DateTime\Chronos::createFromFormat(
                                    'Y/m/d',
                                    $info['ExpireDate'],
                                );
                                $expiry = $expiry->modify('-1day');
                                ?>
                                <div class="text-danger">
                                    繳款截止: {{ $expiry->format('Y/m/d') }}
                                </div>
                            @endif
                        </div>
                        @endif
                    </dd>
                </dl>
            </div>

            <div class="col-md-6">
                <dl class="row mb-4">
                    <dt class="col-4 fw-normal">
                        建立日期
                    </dt>
                    <dd class="col-8 fw-normal">
                        {{ $chronos->toLocalFormat($item->getCreated(), 'Y/m/d H:i') }}
                    </dd>
                </dl>
                <dl class="row mb-0">
                    <dt class="col-4 fw-normal">
                        配送方式
                    </dt>
                    <dd class="col-8 fw-normal">
                        {{ $item->getShipping()->trans($lang) }}
                    </dd>
                </dl>
            </div>
        </div>
    </div>
    @if(!$vendor)
        <hr />

        <div class="c-card__body pt-2">
            <form id="order-control-form" action="." method="post">
                <div class="d-flex justify-content-end gap-3">
                    @if (\App\Service\OrderService::canCancel($item, $subOrders))
                        <button type="button" class="btn btn-xl btn-primary" style="width: 200px"
                            data-dos
                            onclick="u.form('#order-control-form').post('{{ $nav->to('order_cancel')->id($item->getId()) }}')">
                            <i data-spinner></i>
                            取消訂單
                        </button>
                    @endif
                    @if (\App\Service\OrderService::canReturn($item))
                        <button type="button" class="btn btn-xl btn-outline-dark" style="width: 200px"
                            data-bs-toggle="modal"
                            data-bs-target="#return-modal"
                        >
                            <i data-spinner></i>
                            申請退貨
                        </button>

                        @include('global.order-info.return-modal')
                    @endif
                </div>
            </form>
        </div>
    @endif
</div>

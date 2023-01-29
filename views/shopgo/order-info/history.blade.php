<?php

declare(strict_types=1);

$vendor = $vendor ?? 0;
/**
 * @var \Windwalker\Core\Application\AppContext $app
 * @var \App\Entity\OrderHistory $history
 */
$orm = $app->service(Windwalker\ORM\ORM::class);
?>

<div class="c-card">
    <div class="c-card__header">
        訂單歷程
    </div>

    <div class="c-card__body">
        @foreach($items as $keys => $item)
            <?php $history = $orm->mapper(\App\Entity\OrderHistory::class)->toEntity($item) ?>
            <div class="mb-4 c-history-item d-flex align-items-start {{ $keys < 1 ? 'active' : '' }}">
                <div class="me-3">●</div>

                <div class="d-sm-flex align-items-start">
                    <div
                        class="me-3">{{ $chronos->toLocalFormat($item->created, 'Y/m/d H:i') }}</div>
                    <div class="d-flex">
                        <div class="order-history__title me-2">
                            @if ($history->getState())
                                訂單變更為 {{ $history->getState()->trans($lang) }}
                            @else
                                管理員
                            @endif
                        </div>
                        @if ($history->getMessage())
                            :
                            {{ $history->getMessage() }}
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if($vendor)
        <div class="c-card__body py-0">
            <div class="border-bottom"></div>
        </div>

        <div class="c-card__body pt-4">
            <div class="d-flex justify-content-end">
                <button type="button"
                    data-bs-toggle="modal"
                    data-bs-target="#order-state-modal-{{ $order->getId() }}"
                    class="btn btn-xl c-card__btn btn-primary">
                    變更訂單狀態
                </button>
            </div>
        </div>
    @endif
</div>

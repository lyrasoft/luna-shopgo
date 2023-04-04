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

use Lyrasoft\ShopGo\Data\ShippingHistory;
use Lyrasoft\ShopGo\Entity\Order;
use Lyrasoft\ShopGo\Entity\OrderState;
use Lyrasoft\ShopGo\Service\OrderStateService;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;

/**
 * @var Order           $order
 * @var ShippingHistory $history
 */

$id ??= 'shipping-history-modal';

$shippinghistory = $order->getShippingHistory();

$shippinghistory = $shippinghistory->reverse();
?>

<div class="modal fade" id="{{ $id }}" tabindex="-1" role="dialog"
    aria-labelledby="shipping-history-modal-label"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content overflow-hidden">
            <div class="modal-header">
                <h4 class="modal-title" id="shipping-history-modal-label">
                    @lang('shopgo.order.change.state.modal.title')
                </h4>
                <button type="button" class="close btn-close" data-bs-dismiss="modal" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true" class="visually-hidden">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="list-group list-group-flush">
                    @foreach ($shippinghistory as $history)
                    <div class="list-group-item">
                        <div class="text-muted mb-1 small">
                            <i class="fa fa-calendar"></i>
                            {{ $chronos->toLocalFormat($history->getTime()) }}
                        </div>
                        <div>
                            <span class="badge bg-primary">
                                {{ $history->getStatusCode() }}
                            </span>
                            {{ $history->getStatusText() }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

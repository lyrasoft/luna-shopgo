<?php

/**
 * Global variables
 * --------------------------------------------------------------
 * @var $app           \Windwalker\Web\Application                 Global Application
 * @var $package       \Windwalker\Core\Package\AbstractPackage    Package object.
 * @var $view          \Windwalker\Data\Data                       Some information of this view.
 * @var $uri           \Windwalker\Uri\UriData                     Uri information, example: $uri->path
 * @var $datetime      \DateTime                                   PHP DateTime object of current time.
 * @var $helper        \Windwalker\Core\View\Helper\Set\HelperSet  The Windwalker HelperSet object.
 * @var $router        \Windwalker\Core\Router\PackageRouter       Router object.
 * @var $asset         \Windwalker\Core\Asset\AssetManager         The Asset manager.
 */

declare(strict_types=1);

/**
 * @var \App\Entity\Order $item
 */

?>

<div class="c-card">
    <div class="c-card__header">
        發票資訊
    </div>

    <div class="c-card__body">
        <div class="row">
            <div class="col-md-6">
                <dl class="row mb-4">
                    <dt class="col-2 fw-normal">
                        發票類型
                    </dt>
                    <dd class="col-10 fw-normal">
                        {{ $item->getInvoiceType()->trans($lang) }}
                    </dd>
                </dl>
                @if ($item->getInvoiceType()->equals(\App\Enum\OrderInvoice::COMPANY()))
                    <dl class="row mb-0 mb-4 mb-md-0">
                        <dt class="col-2 fw-normal">
                            統一編號
                        </dt>
                        <dd class="col-10 fw-normal">
                            {{ $item->getInvoiceData()->getVat() }}
                        </dd>
                    </dl>
                @endif
            </div>

            <div class="col-md-6">
                <dl class="row mb-4">
                    <dt class="col-2 fw-normal">
                        發票編號
                    </dt>
                    <dd class="col-10 fw-normal">
                        {{ $item->getInvoiceData()->getNo() ?: '-' }}
                    </dd>
                </dl>
                @if ($item->getInvoiceType()->equals(\App\Enum\OrderInvoice::COMPANY()))
                <dl class="row mb-0">
                    <dt class="col-2 fw-normal mb-4">
                        發票抬頭
                    </dt>
                    <dd class="col-10 fw-normal mb-4">
                        {{ $item->getInvoiceData()->getTitle() }}
                    </dd>
                    <dt class="col-2 fw-normal">
                        發票地址
                    </dt>
                    <dd class="col-10 fw-normal">
                        {{ $item->getInvoiceData()->getAddress()->getFullAddress() }}
                    </dd>
                </dl>
                @endif
            </div>
        </div>

        @if ($item->getShipping()->isCod())
            <div class="mt-4 text-danger">
                訂單內的所有商品皆已完成收貨後，將由系統自動開立電子發票
            </div>
        @endif
    </div>
</div>

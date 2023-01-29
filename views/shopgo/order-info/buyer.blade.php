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

?>

<div class="c-card">
    <div class="c-card__header">
        買家資訊
    </div>

    <div class="c-card__body">
        <div class="row">
            <div class="col-md-6">
                <dl class="row mb-4">
                    <dt class="col-4 fw-normal">
                        姓名
                    </dt>
                    <dd class="col-8 fw-normal">
                        {{ $item->getName() }}
                    </dd>
                </dl>
                <dl class="row mb-0 mb-4 mb-md-0">
                    <dt class="col-4 fw-normal">
                        地址
                    </dt>
                    <dd class="col-8 fw-normal">
                        {{ $item->getAddress()->getFullAddress() }}
                    </dd>
                </dl>
            </div>

            <div class="col-md-6">
                <dl class="row mb-4">
                    <dt class="col-4 fw-normal">
                        手機號碼
                    </dt>
                    <dd class="col-8 fw-normal">
                        {{ $item->getPhone() }}
                    </dd>
                </dl>
                <dl class="row mb-0">
                    <dt class="col-4 fw-normal">
                        買家備註
                    </dt>
                    <dd class="col-8 fw-normal">
                        <div class="text-danger">
                            @if ($parent ?? null)
                                {!! html_escape($parent->getNote(), true) !!}
                            @else
                                {!! html_escape($item->getNote(), true) !!}
                            @endif
                        </div>
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</div>

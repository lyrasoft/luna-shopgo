<?php

declare(strict_types=1);

namespace App\view;

/**
 * Global variables
 * --------------------------------------------------------------
 * @var $app       AppContext      Application context.
 * @var $vm        ProductItemView The view model object.
 * @var $uri       SystemUri       System Uri information.
 * @var $chronos   ChronosService  The chronos datetime service.
 * @var $nav       Navigator       Navigator object to build route.
 * @var $asset     AssetService    The Asset manage service.
 * @var $lang      LangService     The language translation service.
 */

use Lyrasoft\ShopGo\Entity\Product;
use Lyrasoft\ShopGo\Entity\ProductVariant;
use Lyrasoft\ShopGo\Module\Front\Product\ProductItemView;
use Lyrasoft\ShopGo\Service\VariantService;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;

/**
 * @var $item                 Product
 * @var $variant              ProductVariant
 * @var $additionalPurchases  ProductVariant[]
 */
?>
<div class="l-additional-purchases mt-5">
    <h3>加價購</h3>

    <div class="l-additional-purchases__slides swiper">
        <div class="l-additional-purchases__wrapper swiper-wrapper">
            @foreach ($additionalPurchases as $additionalPurchase)
                    <?php
                    [$additionalPurchase, $apProduct, $attachment] = $vm->prepareAdditionalPurchase(
                        $additionalPurchase
                    );
                    $priceSet = $additionalPurchase->getPriceSet();
                    $maxQuantity = $attachment->getMaxQuantity() ?: 30;

                    if (VariantService::isOutOfStock($additionalPurchase, $apProduct)) {
                        continue;
                    }

                    $stock = VariantService::getAvailableQuantity($additionalPurchase, $apProduct);
                    $maxQuantity = min($maxQuantity, $stock);
                    ?>
                <div class="c-additional-purchase-product card swiper-slide"
                    data-role="attachment">
                    <div class="card-body d-flex gap-2">
                        <div class="c-additional-purchase-product__cover "
                            style="width: 75px">
                            <div class="ratio ratio-1x1">
                                <img class="object-fit-cover" src="{{ $additionalPurchase->getCover() }}" alt="cover">
                            </div>
                        </div>
                        <div>
                            <h6 class="c-additional-purchase-product__title mb-1">
                                {{ $apProduct->getTitle() }}
                            </h6>

                            @if (!$additionalPurchase->isPrimary())
                                <div class="c-additional-purchase-product__variant text-muted small">
                                    {{ $additionalPurchase->getTitle() }}
                                </div>
                            @endif

                            @if (!$priceSet['final']->eq($priceSet['origin']))
                                <div class="c-additional-purchase-product__base-price text-muted small">
                                    <del>{{ $vm->formatPrice($priceSet['origin']) }}</del>
                                </div>
                            @endif

                            <div class="c-additional-purchase-product__price fs-bold">
                                {{ $vm->formatPrice($priceSet['final'], true) }}
                            </div>

                            <div
                                class="c-additional-purchase-product__actions mt-2 d-flex align-items-center gap-3 ">
                                <div class="form-check">
                                    <input id="input-attachment-{{ $attachment->getId() }}"
                                        type="checkbox" class="form-check-input"
                                        value="{{ $attachment->getId() }}"
                                        data-role="attachment_id"
                                    />
                                    <label for="input-attachment-{{ $attachment->getId() }}"
                                        class="stretched-link">
                                        加購
                                    </label>
                                </div>

                                <div class="" style="z-index: 1">
                                    <select name="attachments[{{ $attachment->getId() }}][quantity]"
                                        class="form-select form-select-sm"
                                        data-role="attachment_quantity"
                                    >
                                        @foreach (range(1, $maxQuantity) as $qty)
                                            <option value="{{ $qty }}">{{ $qty }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </div>
</div>

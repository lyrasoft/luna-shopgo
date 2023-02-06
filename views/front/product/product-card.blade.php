<?php

declare(strict_types=1);

namespace App\view;

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

use Lyrasoft\ShopGo\Entity\Product;
use Lyrasoft\ShopGo\Entity\ProductVariant;
use Lyrasoft\ShopGo\Service\CurrencyService;
use Lyrasoft\ShopGo\Service\VariantService;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;
use Windwalker\Edge\Component\ComponentAttributes;
use Windwalker\ORM\ORM;

/**
 * @var $attributes     ComponentAttributes
 * @var $item           Product|object
 * @var $variant        ProductVariant|object
 * @var $minPrice       float
 * @var $maxPrice       float
 * @var $minStock       float
 * @var $maxStock       float
 */

$attributes->props(
    'item',
    'variant',
    'min-price',
    'max-price',
    'min-stock',
    'max-stock',
);

$orm = $app->service(ORM::class);

$item = $orm->toEntity(Product::class, $item);

$variant ??= $item->variant;
$variant = $orm->toEntity(ProductVariant::class, $variant);

$minPrice ??= $item->min_price ?? 0;
$maxPrice ??= $item->max_price ?? 0;
$minStock ??= $item->min_stock ?? 0;
$maxStock ??= $item->max_stock ?? 0;

$priceSet = $variant->getPriceSet();

$currencyService = $app->service(CurrencyService::class);
$variantService = $app->service(VariantService::class);

$variant = $variantService->prepareVariantView($variant, $item);

$attributes = $attributes->class('card c-product-card');
?>

<article {!! $attributes !!}>
    <div class="card-body d-flex flex-column gap-2">
        <div class="ratio ratio-1x1">
            <img src="{{ $variant->getCover() }}"
                class="c-product-card__cover"
                alt="Cover">
        </div>

        <header class="c-product-card__header">
            <a href="{{ $item->makeLink($nav) }}"
                class="stretched-link">
                <h4 class="m-0">{{ $item->getTitle() }}</h4>
            </a>
        </header>

        <aside class="c-product-card__info">
            @if (!$priceSet['origin']->eq($priceSet['final']))
                <del>
                    {{ $currencyService->format($priceSet['origin']) }}
                </del>
            @endif

            <span class="fs-5">
                {{ $currencyService->format($priceSet['final']) }}
            </span>
        </aside>

        <div class="c-product-card__actions mt-auto d-flex gap-1"
            style="position: relative; z-index: 1">
            <button type="button" class="btn btn-primary flex-grow-1">
                <i class="fa fa-cart-plus"></i>
                加入購物車
            </button>
            <button type="button" class="btn btn-outline-primary">
                <i class="far fa-heart"></i>
            </button>
        </div>
    </div>
</article>

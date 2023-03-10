<?php

declare(strict_types=1);

namespace App\View;

/**
 * Global variables
 * --------------------------------------------------------------
 * @var $app       AppContext      Application context.
 * @var $vm        \Lyrasoft\ShopGo\Module\Front\Product\ProductListView The view model object.
 * @var $uri       SystemUri       System Uri information.
 * @var $chronos   ChronosService  The chronos datetime service.
 * @var $nav       Navigator       Navigator object to build route.
 * @var $asset     AssetService    The Asset manage service.
 * @var $lang      LangService     The language translation service.
 */

use Lyrasoft\ShopGo\Entity\Product;
use Lyrasoft\ShopGo\Module\Front\Product\ProductListView;
use Lyrasoft\ShopGo\Script\ShopGoScript;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;
use Windwalker\ORM\ORM;

/**
 * @var $item Product
 */

$shopGoScript = $app->service(ShopGoScript::class);
$shopGoScript->wishlistButton();
$shopGoScript->productCart();

$orm = $app->service(ORM::class);

?>

@extends('global.body')

@section('content')
    <div class="l-product-list container my-5">
        <div class="row">
            @foreach ($items as $item)
                    <?php
                    $maxPrice = $item->max_price;
                    $minPrice = $item->min_price;
                    $maxStock = $item->max_stock;
                    $minStock = $item->min_stock;
                    ?>
                <div class="col-lg-3 my-4">
                    <x-front.product.product-card
                        class="h-100"
                        :item="$item"
                        :variant="$item->variant"
                        :min-price="$minPrice"
                        :max-price="$maxPrice"
                        :min-stock="$minStock"
                        :max-stock="$maxStock"
                        :favorited="$item->favorited"
                    ></x-front.product.product-card>
                </div>
            @endforeach
        </div>

        <div class="mt-3">
            {!! $pagination->render() !!}
        </div>
    </div>
@stop

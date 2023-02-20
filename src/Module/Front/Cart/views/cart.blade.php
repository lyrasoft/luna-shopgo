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

use Lyrasoft\Luna\User\UserService;
use Lyrasoft\ShopGo\Script\ShopGoScript;
use Unicorn\Image\ImagePlaceholder;
use Unicorn\Script\UnicornScript;
use Unicorn\Script\VueScript;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;

$imagePlaceholder = $app->service(ImagePlaceholder::class);
$userService = $app->service(UserService::class);

$shopGoScript = $app->service(ShopGoScript::class);
$shopGoScript->vueUtilities();
$shopGoScript->productCart();

$vueScript = $app->service(VueScript::class);
$vueScript->vue();
$vueScript->animate();

$uniScript = $app->service(UnicornScript::class);
$uniScript->data('cart.props', [
    'user' => $userService->isLogin() ? $userService->getUser() : null,
]);
$uniScript->data('image.default', $imagePlaceholder->placeholderSquare());

$uniScript->addRoute('@cart_ajax');
$uniScript->addRoute('@address_ajax');

?>

@extends('global.body')

@section('content')
    <div class="l-cart-page container my-5">
        <cart-app>
            <form id="cart-form" ref="form" action="." method="post">
                <div class="row">
                    <div class="col-lg-8 l-cart-page__content">
                        <header class="d-flex align-items-center justify-content-between mb-4">
                            <h3 class="m-0">購物車</h3>

                            <div>
                                <a href="javascript://">
                                    <i class="fa fa-times"></i>
                                    移除所有商品
                                </a>
                            </div>
                        </header>

                        <div class="l-cart-data">

                            {{-- Cart Items --}}
                            <x-cart-items></x-cart-items>

                            {{-- Addresses --}}
                            <div class="">
                                <address-form type="shipping"
                                    title="收件者地址"
                                    :user="user"
                                    v-model="shippingData"
                                ></address-form>
                                <address-form type="payment"
                                    title="購買者地址"
                                    :user="user"
                                    :sync-data="shippingData"
                                    v-model="paymentData"
                                ></address-form>
                            </div>

                            {{-- Shippings --}}
                            <div class="l-shippings">
                                <h3>貨運方式</h3>

                                <shipping-item v-for="(shipping, i) of shippings" :key="shipping.id"
                                    :shipping="shipping"
                                    :i="i"
                                    :selected="shippingId === shipping.id"
                                    @selected="shippingId = shipping.id"
                                >
                                </shipping-item>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 l-cart-page__sidebar">
                        <div class="card">
                            <div class="card-body l-cart-coupons border-bottom">

                            </div>

                            <div v-if="loaded" class="card-body l-cart-totals text-end">
                                <div v-if="totals.total" class="l-cart-total d-flex justify-content-between gap-1 mb-1 w-100">
                                    <div class="l-cart-total__label">
                                        訂單小計
                                    </div>

                                    <div class="l-cart-total__value">
                                        @{{ $formatPrice(totals.total.price, true) }}
                                    </div>
                                </div>

                                <div class="l-cart-total d-flex justify-content-between gap-1 mb-1 w-100"
                                    v-for="total of filteredTotals">
                                    <div class="l-cart-total__label">
                                        @{{ total.label }}
                                    </div>

                                    <div class="l-cart-total__value">
                                        @{{ $formatPrice(total.price, true) }}
                                    </div>
                                </div>

                                <div v-if="totals.grand_total" class="l-cart-total d-flex justify-content-between gap-1 mb-1 w-100 fs-5 fw-bold">
                                    <div class="l-cart-total__label">
                                        訂單總計
                                    </div>

                                    <div class="l-cart-total__value">
                                        @{{ $formatPrice(totals.grand_total.price, true) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </cart-app>
    </div>

    <x-address-form></x-address-form>
    <x-shipping-item></x-shipping-item>
    <x-vue.cascade-select></x-vue.cascade-select>
@stop

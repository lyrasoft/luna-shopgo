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

use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;

?>

<div class="l-shippings">
    <h3>貨運方式</h3>

    <div>
        <div class="card my-3" v-for="shipping of shippings" :key="shipping.id">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="form-check">
                    <input type="radio" name="shipping[id]" v-model="shippingId"
                        class="form-check-input"
                    />
                </div>
                <div class="">
                    <div class="ratio ratio-1x1"
                        style="width: 45px">
                        <img :src="shipping.image" alt="cover">
                    </div>
                </div>
                <div>
                    <h5 class="m-0">
                        @{{ shipping.title }}
                    </h5>
                    <div v-if="shipping.subtitle">
                        @{{ shipping.subtitle }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

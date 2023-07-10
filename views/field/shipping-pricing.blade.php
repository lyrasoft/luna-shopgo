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

use Lyrasoft\ShopGo\Field\ShippingPricingField;
use Lyrasoft\ShopGo\Script\ShopGoScript;
use Unicorn\Script\BootstrapScript;
use Unicorn\Script\UnicornScript;
use Unicorn\Script\VueScript;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;
use Windwalker\DOM\DOMElement;

/**
 * @var $field ShippingPricingField
 * @var $input DOMElement
 * @var $values array
 */

$id = $field->getId('__app');
$input->removeAttribute('value');
$input->setAttribute(':value', 'finalResult');

$app->service(ShopGoScript::class)->vueUtilities();

$vueScript = $app->service(VueScript::class);
$vueScript->vue();
$vueScript->draggable();
$vueScript->animate();

$uniScript = $app->service(UnicornScript::class);
$uniScript->data(
    $id . '.props',
    [
        'pricing' => $values
    ]
);

$uniScript->addRoute('location_modal', $nav->to('location_list')->layout('modal'));
$uniScript->addRoute('category_modal', $nav->to('category_list')->var('type', 'location')->layout('modal'));

$uniScript->importThen(
    '@shopgo/field/shipping-pricing.js',
    "u.module('#$id', 'shipping.pricing', (el) => ShippingPricing.create(el))",
    true
);
?>

<shipping-pricing-app id="{{ $id }}">
    <div class="c-shipping-pricing">

        {{-- Global --}}
        <div class="card c-pricing c-pricing--location-categories mb-5">
            <div class="card-body p-2 d-flex gap-3 align-items-center">
                <div class="me-auto">
                    <h5 class="m-0">
                        @lang('shopgo.shipping.pricing.global.title')
                    </h5>
                </div>
                <div class="form-check form-switch">
                    <label for="input-free-global"
                        class="form-check-label"
                    >
                        @lang('shopgo.shipping.field.free')
                    </label>
                    <input type="checkbox" id="input-free-global"
                        v-model="global.free"
                        class="form-check-input"
                    />
                </div>
                <div class="">
                    <button type="button" class="btn btn-primary"
                        style="min-width: 150px"
                        @click="configurePricing(global)"
                    >
                        @lang('shopgo.shipping.pricing.configuration') (@{{ global.pricing.length }})
                    </button>
                </div>
            </div>
        </div>

        {{-- Location Categories --}}
        <div class="card c-pricing c-pricing--location-categories mb-5">
            <div class="card-body p-2 d-flex align-items-center">
                <div>
                    <h5 class="m-0">
                        @lang('shopgo.shipping.pricing.location.categories')
                    </h5>
                </div>
                <div class="ms-auto">
                    <button type="button" class="btn btn-outline-primary"
                        style="min-width: 150px"
                        @click="openLocationCategorySelector"
                    >
                        <i class="fa fa-plus"></i>
                        @lang('shopgo.shipping.pricing.button.select.location.category')
                    </button>
                </div>
            </div>

            <div class="list-group list-group-flush">
                <div v-for="(category, i) of locationCategories"
                    class="list-group-item d-flex align-items-center gap-3">
                    <div>
                        <span class="badge bg-secondary">
                            #@{{ category.id }}
                        </span>
                    </div>
                    <div class="me-auto">
                        <span>
                            @{{ category.title }}
                        </span>
                    </div>

                    <div class="form-check form-switch">
                        <label :for="`input-free-category-${category.id}`"
                            class="form-check-label"
                        >
                            @lang('shopgo.shipping.field.free')
                        </label>
                        <input type="checkbox" :id="`input-free-category-${category.id}`"
                            v-model="category.free"
                            class="form-check-input"
                        />
                    </div>

                    <div>
                        <button type="button" class="btn btn-primary btn-sm"
                            @click="configurePricing(category)">
                            @lang('shopgo.shipping.pricing.configuration')
                            (@{{ category.pricing.length }})
                        </button>
                        <button type="button" class="btn btn-danger btn-sm ms-3"
                            @click="locationCategories.splice(i, 1)">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Locations --}}
        <div class="card c-pricing c-pricing--locations mb-5">
            <div class="card-body p-2 d-flex align-items-center">
                <div>
                    <h5 class="m-0">
                        @lang('shopgo.shipping.pricing.location')
                    </h5>
                </div>
                <div class="ms-auto">
                    <button type="button" class="btn btn-outline-primary"
                        style="min-width: 150px"
                        @click="openLocationSelector"
                    >
                        <i class="fa fa-plus"></i>
                        @lang('shopgo.shipping.pricing.button.select.location')
                    </button>
                </div>
            </div>

            <div class="list-group list-group-flush">
                <div v-for="location of locations"
                    class="list-group-item d-flex align-items-center gap-3">
                    <div>
                        <span class="badge bg-secondary">
                            #@{{ location.id }}
                        </span>
                    </div>
                    <div class="me-auto">
                        <span v-if="location.path.length > 0" class="text-muted">
                            @{{ location.path.join(' / ') }} /
                        </span>
                        <span>
                            @{{ location.title }}
                        </span>
                    </div>

                    <div class="form-check form-switch">
                        <label :for="`input-free-location-${location.id}`"
                            class="form-check-label"
                        >
                            @lang('shopgo.shipping.field.free')
                        </label>
                        <input type="checkbox" :id="`input-free-location-${location.id}`" v-model="location.free"
                            class="form-check-input"
                        />
                    </div>

                    <div>
                        <button type="button" class="btn btn-primary btn-sm"
                            @click="configurePricing(location)">
                            @lang('shopgo.shipping.pricing.configuration')
                            (@{{ location.pricing.length }})
                        </button>
                        <button type="button" class="btn btn-danger btn-sm ms-3"
                            @click="locations.splice(i, 1)">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-none">
            {!! $input !!}

{{--            <input type="hidden" name="{{ $field->getName() }}[global][free]" :value="global.free ? 1 : 0" />--}}
{{--            <template v-for="(seg, i) of global.pricing">--}}
{{--                <input type="hidden" :name="`{{ $field->getName() }}[global][${i}][threshold]`" :value="seg.threshold" />--}}
{{--                <input type="hidden" :name="`{{ $field->getName() }}[global][${i}][threshold]`" :value="seg.threshold" />--}}
{{--            </template>--}}
        </div>

        <uni-iframe-modal ref="selectModal"></uni-iframe-modal>

        <div class="modal fade" id="pricing-modal" ref="pricingModal"
            tabindex="-1" role="dialog" aria-labelledby="pricing-modal-label"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="pricing-modal-label">
                            @lang('shopgo.shipping.pricing.configure.modal.title')
                        </h4>
                        <button type="button" class="close btn-close" data-bs-dismiss="modal" data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true" class="visually-hidden">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" v-if="currentItem">
                        <table class="table w-100">
                            <thead>
                            <tr>
                                <th>
                                    @lang('shopgo.shipping.pricing.product.price')
                                </th>
                                <th>
                                    @lang('shopgo.shipping.field.shipping.fee')
                                </th>
                                <th class="text-end">
                                    #
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(segment, i) of currentItem.pricing">
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-text">
                                            &gt;=
                                        </div>
                                        <input type="number" v-model="segment.threshold" step="0.0001" class="form-control"
                                            :disabled="i === 0"
                                        />
                                    </div>
                                </td>
                                <td>
                                    <input type="number" v-model="segment.fee" step="0.0001" class="form-control" />
                                </td>
                                <td class="text-end text-nowrap">
                                    <button type="button"
                                        class="btn btn-success btn-sm"
                                        @click="addPricingSegment(i)"
                                    >
                                        <i class="fa fa-plus"></i>
                                    </button>
                                    <button type="button"
                                        class="btn btn-danger btn-sm"
                                        @click="removePricingSegment(i)"
                                        :disabled="i === 0"
                                    >
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</shipping-pricing-app>

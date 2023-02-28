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
<script id="c-shipping-item" type="x-template">
<div class="card my-3"
    :class="[ selected ? 'border border-primary' : '' ]">
    <div class="card-body d-flex align-items-center gap-3">
        <div class="form-check">
            <input type="radio"
                :id="`input-shipping-id-${shipping.id}`"
                name="checkout[shipping][id]"
                :value="shipping.id"
                class="form-check-input"
                @change="onSelected"
                :checked="selected"
            />
            <label :for="`input-shipping-id-${shipping.id}`"
                class="stretched-link"
                style="cursor: pointer;"
            ></label>
        </div>
        <div class="">
            <div class="ratio ratio-1x1"
                style="width: 45px">
                <img :src="shipping.image || imageDefault" alt="cover">
            </div>
        </div>
        <div>
            <h5 class="m-0">
                @{{ shipping.title }}
            </h5>
            <div v-if="shipping.subtitle" class="text-success">
                @{{ shipping.subtitle }}
            </div>
        </div>

        <div class="ms-auto">
            <span class="fs-5">
            @{{ $formatPrice(shipping.fee, true) }}
            </span>
        </div>
    </div>

    <div v-if="shipping.description.trim()" class="card-body border-top ps-5">
        <div class="position-relative" style="z-index: 1"
            v-html="shipping.description">

        </div>
    </div>

    <transition name="fade" mode="out-in">
        <div
            ref="form"
            style="display: none; postion: relative; z-index: 1; overflow: hidden; animation-duration: .3s">
            <div v-if="shipping.checkoutForm && selected"
                class="card-body border-top"
                v-html="shipping.checkoutForm">
            </div>
        </div>
    </transition>
</div>
</script>

<script>
    function shippingItem() {
      const { ref, toRefs, reactive, computed, watch, onMounted, inject, nextTick } = Vue;

      return {
        template: '#c-shipping-item',
        props: {
          shipping: Object,
          i: Number,
          selected: Boolean,
        },
        setup(props, { emit }) {
          const state = reactive({
            uid: u.uid(),
            data: {},
            selected: false,
            imageDefault: u.data('image.default'),
          });

          watch(() => props.selected, () => {
            state.selected = props.selected;

            setTimeout(() => {
              if (state.selected) {
                const scripts = form.value.querySelectorAll('.card-body script');
                for (const script of scripts) {
                  eval(script.textContent);
                }

                u.$ui.slideDown(form.value);
              } else {
                u.$ui.slideUp(form.value);
              }
            }, 0);
          });

          function onSelected(e) {
            state.selected = true;

            emit('selected');
          }

          const form = ref(null);

          return {
            ...toRefs(state),
            form,

            onSelected,
          };
        }
      };
    }
</script>

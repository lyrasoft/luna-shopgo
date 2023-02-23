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
<script id="c-payment-item" type="x-template">
<div class="card my-3"
    :class="[ selected ? 'border border-primary' : '' ]">
    <div class="card-body d-flex align-items-center gap-3">
        <div class="form-check">
            <input type="radio"
                :id="`input-payment-id-${payment.id}`"
                name="checkout[payment][id]"
                :value="payment.id"
                class="form-check-input"
                @change="onSelected"
                :checked="selected"
            />
            <label :for="`input-payment-id-${payment.id}`"
                class="stretched-link"
                style="cursor: pointer;"
            ></label>
        </div>
        <div class="">
            <div class="ratio ratio-1x1"
                style="width: 45px">
                <img class="object-fit-cover" :src="payment.image" alt="cover">
            </div>
        </div>
        <div>
            <h5 class="m-0">
                @{{ payment.title }}
            </h5>
            <div v-if="payment.subtitle" class="text-success">
                @{{ payment.subtitle }}
            </div>
        </div>

        <div class="ms-auto">

        </div>
    </div>

    <div v-if="payment.description.trim()" class="card-body border-top ps-5">
        <div class="position-relative" style="z-index: 1"
            v-html="payment.description">

        </div>
    </div>

    <transition name="fade" mode="out-in">
        <div
            ref="optionLayout"
            style="display: none; overflow: hidden; animation-duration: .3s">
            <div v-if="payment.optionLayout && selected"
                class="card-body border-top"
                v-html="payment.optionLayout"
                >
            </div>
        </div>
    </transition>
</div>
</script>

<script>
    function paymentItem() {
      const { ref, toRefs, reactive, computed, watch, onMounted, inject, nextTick } = Vue;

      return {
        template: '#c-payment-item',
        props: {
          payment: Object,
          i: Number,
          selected: Boolean,
        },
        setup(props, { emit }) {
          const state = reactive({
            uid: u.uid(),
            data: {},
            selected: false,
          });

          watch(() => props.selected, () => {
            state.selected = props.selected;

            setTimeout(() => {
              if (state.selected) {
                u.$ui.slideDown(optionLayout.value);
              } else {
                u.$ui.slideUp(optionLayout.value);
              }
            }, 0);
          });

          function onSelected(e) {
            state.selected = true;

            emit('selected');
          }

          const optionLayout = ref(null);

          return {
            ...toRefs(state),
            optionLayout,

            onSelected,
          };
        }
      };
    }
</script>

<script setup lang="ts">
import { data as udata, slideDown, slideUp, uid } from '@windwalker-io/unicorn-next';
import { watch, ref } from 'vue';
import { Payment } from '~shopgo/types';

const props = defineProps<{
  payment: Payment;
  i: number;
  selected: boolean;
}>();

const emit = defineEmits<{
  (e: 'selected'): void;
}>();

// split state into individual refs
const uidRef = ref(uid());
const data = ref({});
const selectedRef = ref(props.selected);
const imageDefault = ref(udata('image.default'));

watch(() => props.selected, () => {
  selectedRef.value = props.selected;

  setTimeout(() => {
    if (selectedRef.value) {
      slideDown(optionLayout.value!);
    } else {
      slideUp(optionLayout.value!);
    }
  }, 0);
});

function onSelected() {
  selectedRef.value = true;

  emit('selected');
}

const optionLayout = ref<HTMLDivElement>();
</script>

<template>
  <div class="card"
    :class="[ selectedRef ? 'border border-primary' : '' ]">
    <div class="card-body d-flex align-items-center gap-3">
      <div class="form-check">
        <input type="radio"
          :id="`input-payment-id-${payment.id}`"
          name="checkout[payment][id]"
          :value="payment.id"
          class="form-check-input"
          @change="onSelected"
          :checked="selectedRef"
        />
        <label :for="`input-payment-id-${payment.id}`"
          class="stretched-link"
          style="cursor: pointer;"
        ></label>
      </div>
      <div class="">
        <div class="ratio ratio-1x1"
          style="width: 45px">
          <img class="object-fit-cover" :src="payment.image || imageDefault" alt="cover">
        </div>
      </div>
      <div>
        <h5 class="m-0">
          {{ payment.title }}
        </h5>
        <div v-if="payment.subtitle" class="text-success">
          {{ payment.subtitle }}
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
        <div v-if="payment.optionLayout && selectedRef"
          class="card-body border-top"
          v-html="payment.optionLayout"
        >
        </div>
      </div>
    </transition>
  </div>
</template>

<style scoped>

</style>

<script setup lang="ts">
import { AddressFormData, Payment, Shipping, User } from '~shopgo/types';

defineProps<{
  user: User | null;
  shippings: Shipping[];
  payments: Payment[];
}>();

const paymentData = defineModel<AddressFormData>('payment', {
  required: true,
});

const shippingData = defineModel<AddressFormData>('shipping', {
  required: true,
});

const paymentId = defineModel<string | number>('payment-id', {
  required: true,
});

const shippingId = defineModel<string | number>('shipping-id', {
  required: true,
});

const note = defineModel<string>('note', {
  default: '',
});
</script>

<template>
<div>
  <slot :user :shippings :payments :shippingData :paymentData :shippingId :paymentId :note></slot>
</div>
</template>

<style scoped>

</style>

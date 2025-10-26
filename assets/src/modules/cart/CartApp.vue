<script setup lang="ts">
import { __, data, debounce, route, simpleAlert, useHttpClient, useStack } from '@windwalker-io/unicorn-next';
import { computed, nextTick, onMounted, ref, watch } from 'vue';
import { ComponentExposed } from 'vue-component-type-helpers';
import { vTooltip } from '~shopgo/directives';
import AddressForm from '~shopgo/modules/cart/components/AddressForm.vue';
import CartListItem from '~shopgo/modules/cart/components/CartListItem.vue';
import PaymentItem from '~shopgo/modules/cart/components/PaymentItem.vue';
import ShippingItem from '~shopgo/modules/cart/components/ShippingItem.vue';
import { CartData, CartItem, Discount, OrderTotal, Payment, Shipping, User } from '~shopgo/types';

const props = defineProps<{
  user: User | null;
  checkoutData: any;
}>();

const loaded = ref(false);
const items = ref<CartItem[]>([]);
const totals = ref<Record<string, any>>({});
const coupons = ref<Discount[]>([]);
const paymentId = ref(props.checkoutData?.payment?.id || '');
const paymentData = ref(props.checkoutData?.payment_data || {});
const shippingId = ref(props.checkoutData?.shipping?.id || '');
const shippingData = ref(props.checkoutData?.shipping_data || {});
const shippings = ref<Shipping[]>([]);
const payments = ref<Payment[]>([]);
const receiptData = ref<any>({});
const code = ref('');
const note = ref(props.checkoutData?.note || '');
const loading = ref(false);
const partialCheckout = ref(data('partial.checkout'));

const form = document.querySelector<HTMLFormElement>('#cart-form')!;
const toggleAllInput = ref<HTMLInputElement>();
const loadingStack = useStack('loading');

loadingStack.observe((stack, length) => {
  loading.value = length > 0;
});

init();

function popLoading(wait = 300) {
  setTimeout(() => {
    loadingStack.pop();
  }, wait);
}

const afterItemsChanged = debounce(function () {
  return loadItems();
}, 300);

async function loadItems(updateShippings = true) {
  loadingStack.push(true);

  const { get, isAxiosError } = await useHttpClient();

  try {
    const res = await get(
      '@cart_ajax/getItems',
      {
        params: {
          location_id: shippingData.value.locationId,
          shipping_id: shippingId.value,
          payment_id: paymentId.value,
        }
      }
    );

    await setCartData(res.data.data, updateShippings);

    return res;
  } catch (e) {
    console.error(e);
    if (isAxiosError(e)) {
      simpleAlert(e.message, '', 'warning');
    }
  } finally {
    popLoading();
  }
}

async function setCartData(data: CartData, updateShippings = true) {
  items.value = data.items;
  totals.value = data.totals;
  coupons.value = data.coupons;

  if (updateShippings) {
    return await loadShippings();
  }

  return;
}

// Toggle checks
watch(items, () => {
  updateToggleAll();
}, { deep: true });

const itemChecks = computed(() => {
  return items.value.map((item: any) => {
    if (item.options.checked == null) {
      return true;
    }

    return item.options.checked;
  });
});

const checks = computed(() => itemChecks.value.filter(checked => checked === true).length);
const unchecks = computed(() => itemChecks.value.filter(checked => checked === false).length);

function updateToggleAll() {
  if (!toggleAllInput.value) {
    return;
  }

  toggleAllInput.value.checked = false;
  toggleAllInput.value.indeterminate = false;

  if (checks.value > 0 && unchecks.value === 0) {
    toggleAllInput.value.checked = true;
  } else if (unchecks.value > 0 && checks.value === 0) {
    toggleAllInput.value.checked = false;
  } else if (checks.value > 0 && unchecks.value > 0) {
    toggleAllInput.value.indeterminate = true;
  }
}

function toggleChecked() {
  if (!toggleAllInput.value) {
    return;
  }

  for (const item of items.value) {
    item.options.checked = toggleAllInput.value.checked;
  }

  updateChecks();
}

const updateChecks = debounce(async () => {
  const checks: Record<string, '1' | '0'> = {};

  for (const item of items.value) {
    checks[item.key] = item.options.checked ? '1' : '0';
  }

  loadingStack.push(true);

  const { post, isAxiosError } = await useHttpClient();

  try {
    const res = await post('@cart_ajax/updateChecks', { checks });

    return await loadItems();
  } catch (e) {
    console.error(e);
    if (isAxiosError(e)) {
      simpleAlert(e.message, '', 'warning');
    }
  } finally {
    popLoading();
  }
}, 300);

onMounted(() => {
  calcNavAndStickySidebar(form);
});

function calcNavAndStickySidebar(form: HTMLFormElement, offsets = 30) {
  const navbar = document.querySelector<HTMLDivElement>('header .navbar, .navbar');

  if (!navbar) {
    return;
  }

  const top = navbar.clientHeight + offsets;

  form.style.setProperty('--sidebar-offsets-top', top + 'px');
}

async function init() {
  await loadItems();

  loaded.value = true;
}

// Actions
async function removeItem(item: CartItem, i: number) {
  loadingStack.push(true);

  const { delete: del, isAxiosError } = await useHttpClient();

  try {
    const res = await del(`@cart_ajax/removeItem?key=${item.key}`);

    return await afterItemsChanged();
  } catch (e) {
    console.error(e);
    if (isAxiosError(e)) {
      simpleAlert(e.message, '', 'warning');
    }
  } finally {
    loadingStack.pop();
  }
}

async function clearCart() {
  loadingStack.push(true);

  const { put, isAxiosError } = await useHttpClient();

  try {
    await put(`@cart_ajax/clearCart`);

    await loadItems();

    await simpleAlert(
      __('shopgo.cart.message.items.removed'),
      __('shopgo.cart.message.will.back.to.home'),
      'success'
    );

    location.href = route('home');
  } catch (e) {
    console.error(e);
    if (isAxiosError(e)) {
      simpleAlert(e.message, '', 'warning');
    }
  } finally {
    loadingStack.pop();
  }
}

// Quantity
async function changeItemQuantity(item: CartItem, offsets: number) {
  item.quantity += offsets;

  item.quantity = Math.max(item.quantity, 1);

  await updateQuantities(item);
}

const updateQuantities = debounce(async (item: CartItem) => {
  item.quantity = Math.max(item.quantity, 1);

  const values: Record<string, number> = {};

  for (const item of items.value) {
    values[item.key] = item.quantity;
  }

  loadingStack.push(true);

  const { post, isAxiosError } = await useHttpClient();

  try {
    const res = await post('@cart_ajax/updateQuantities', { values });

    return await loadItems();
  } catch (e) {
    console.error(e);
    if (isAxiosError(e)) {
      simpleAlert(e.message, '', 'warning');
    }
  } finally {
    popLoading();
  }
}, 300);

// Code / Coupons
async function addCode() {
  if (code.value === '') {
    return;
  }

  loadingStack.push(true);

  const { post, isAxiosError } = await useHttpClient();

  try {
    const res = await post('@cart_ajax/addCode', { code: code.value });

    code.value = '';

    await loadItems();
  } catch (e) {
    console.error(e);
    if (isAxiosError(e)) {
      simpleAlert(e.message, '', 'warning');
    }
  } finally {
    popLoading();
  }
}

async function removeCode(id: number | string) {
  loadingStack.push(true);

  const { delete: del, isAxiosError } = await useHttpClient();

  try {
    const res = await del('@cart_ajax/removeCode', { id });

    await loadItems();
  } catch (e) {
    console.error(e);
    if (isAxiosError(e)) {
      simpleAlert(e.message, '', 'warning');
    }
  } finally {
    popLoading();
  }
}

// Totals
const filteredTotals = computed(() => {
  const _totals: any[] = [];

  for (const name in totals.value) {
    if (name === 'total') {
      continue;
    }

    if (name === 'grand_total') {
      continue;
    }

    const total = totals.value[name];

    if (Number(total.price) === 0) {
      continue;
    }

    _totals.push(total);
  }

  return _totals;
});

// Shippings
watch(() => shippingData.value.locationId, () => {
  loadShippings();
});
watch(() => shippingId.value, () => {
  loadItems(false);
});

const selectedShipping = computed(() => {
  return shippings.value.find((item: any) => String(item.id) === String(shippingId.value));
});

const loadShippings = debounce(async function () {
  loadingStack.push(true);

  const { get, isAxiosError } = await useHttpClient();

  try {
    const res = await get(`@cart_ajax/shippings?location_id=${shippingData.value.locationId}`);

    shippings.value = res.data.data;

    await nextTick();
    await nextTick();

    if (shippings.value.length > 0) {
      if (!selectedShipping.value) {
        shippingId.value = shippings.value[0].id;
      }
    } else {
      shippingId.value = null;
    }
  } catch (e) {
    console.error(e);
    if (isAxiosError(e)) {
      simpleAlert(e.message, '', 'warning');
    }
  } finally {
    popLoading();
  }
}, 300);

// Payments
watch(() => [shippingData.value.locationId, shippingId.value], () => {
  loadPayments();
});

const selectedPayment = computed(() => {
  return payments.value.find((item: any) => item.id === paymentId.value);
});

const loadPayments = debounce(async function () {
  loadingStack.push(true);

  const { get, isAxiosError } = await useHttpClient();

  try {
    const res = await get(
      `@cart_ajax/payments`,
      {
        params: {
          location_id: shippingData.value.locationId,
          shipping_id: shippingId.value
        }
      }
    );

    payments.value = res.data.data;

    await nextTick();
    await nextTick();

    if (payments.value.length > 0) {
      if (!payments.value.find((payment: any) => payment.id === paymentId.value)) {
        paymentId.value = payments.value[0].id;
      }
    } else {
      paymentId.value = null;
    }
  } catch (e) {
    console.error(e);
    if (isAxiosError(e)) {
      simpleAlert(e.message, '', 'warning');
    }
  } finally {
    popLoading();
  }
}, 300);

// Checkout
const canCheckout = computed(() => {
  if (checks.value === 0) {
    return false;
  }

  if (!shippingData.value.locationId) {
    return false;
  }

  if (!paymentData.value.locationId) {
    return false;
  }

  if (!shippingId.value) {
    return false;
  }

  if (!paymentId.value) {
    return false;
  }

  return true;
});

const shippingForm = ref<ComponentExposed<typeof AddressForm>>();
const paymentForm = ref<ComponentExposed<typeof AddressForm>>();

function checkout() {
  if (checks.value === 0) {
    console.warn('No checked items');
    return;
  }

  if (Number(totals.value.grand_total.price) < 0) {
    swal('Cannot process cart with negative prices.', '', 'warning');
    return;
  }

  for (const item of items.value) {
    if (Number(item.priceSet.final_total.price) < 0) {
      swal('Cannot process product items with negative prices.', '', 'warning');
      return;
    }

    if (Number(item.priceSet.attached_final_total.price) < 0) {
      swal('Cannot process product items with negative prices.', '', 'warning');
      return;
    }
  }

  if (shippingForm.value && !shippingForm.value.validate()) {
    console.log('Shipping Validate Fail');
    return;
  }

  if (paymentForm.value && !paymentForm.value.validate()) {
    console.log('Payment Validate Fail');
    return;
  }

  if (!form.checkValidity()) {
    form.reportValidity();

    const invalid = form.querySelector<HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement>(':invalid');

    if (invalid && !isVisible(invalid) && invalid.dataset.validationMessage) {
      simpleAlert(invalid.dataset.validationMessage);
    }

    return;
  }

  loading.value = true;

  form.requestSubmit();
}

function isVisible(el: HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement) {
  return !!(el.offsetWidth || el.offsetHeight || el.getClientRects().length);
}
</script>

<template>

  <div class="row">
    <div class="col-lg-8 l-cart-page__content">
      <!-- Header -->
      <header class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center gap-2">
          <h3 class="m-0">{{ $lang('shopgo.cart.title') }}</h3>
          <div v-if="partialCheckout" class="form-check">
            <input id="input-toggle-all" type="checkbox" class="form-check-input"
              ref="toggleAllInput"
              @click="toggleChecked"
            />
            <label for="input-toggle-all" class="form-check-label">
              {{ $lang('shopgo.cart.toggle.all') }}
            </label>
          </div>
          <div v-if="loading" class="spinner spinner-border-sm spinner-border"
            data-cloak>

          </div>
        </div>

        <div>
          <a href="javascript://"
            @click="clearCart">
            <i class="fa fa-times"></i>
            {{ $lang('shopgo.cart.button.remove.all') }}
          </a>
        </div>
      </header>

      <!-- Body Loading -->
      <div data-loading>
        <div class="d-flex py-5">
          <span class="spinner spinner-grow spinner-lg mx-auto"></span>
        </div>
      </div>

      <div class="l-cart-data">

        <!-- Cart Items -->
        <div class="l-cart-items">
          <CartListItem v-for="(item, i) of items" :key="item.key"
            :item
            :has-checkbox="partialCheckout"
            @remove-item="removeItem(item, i)"
            @update-quantity="updateQuantities(item)"
            @change-item-quantity="changeItemQuantity(item, $event)"
            @update-checks="updateChecks"
          />
        </div>

        <!-- Addresses -->
        <div class="">
          <AddressForm type="payment"
            :title="$lang('shopgo.cart.payment.data.title')"
            :user="user"
            v-model="paymentData"
            ref="paymentForm"
          ></AddressForm>
          <AddressForm type="shipping"
            :title="$lang('shopgo.cart.shipping.data.title')"
            :user="user"
            v-model="shippingData"
            :sync-data="paymentData"
            ref="shippingForm"
          ></AddressForm>
        </div>

        <!-- Shippings -->
        <div class="l-shippings mb-4">
          <h3>{{ $lang('shopgo.cart.shipping.title') }}</h3>

          <div v-if="shippings.length > 0">
            <ShippingItem v-for="(shipping, i) of shippings" :key="shipping.id"
              style="animation-duration: .1s"
              :shipping="shipping"
              :i="i"
              :selected="shippingId === shipping.id"
              v-on:selected="shippingId = shipping.id"
            >
            </ShippingItem>
          </div>
          <div v-else class="card bg-light">
            <div class="card-body py-5 text-center">
              <template v-if="loading">
                <span class="spinner spinner-border"></span>
              </template>
              <template v-else-if="shippingData.locationId">
                {{ $lang('shopgo.cart.text.no.shippings') }}
              </template>
              <template v-else>
                {{ $lang('shopgo.cart.text.select.location.first') }}
              </template>
            </div>
          </div>
        </div>

        <!-- Payments -->
        <div class="l-payments mb-4">
          <h3>{{ $lang('shopgo.cart.payment.title') }}</h3>

          <div v-if="payments.length > 0">
            <PaymentItem v-for="(payment, i) of payments" :key="payment.id"
              style="animation-duration: .1s"
              :payment="payment"
              :i="i"
              :selected="paymentId === payment.id"
              v-on:selected="paymentId = payment.id"
            >
            </PaymentItem>
          </div>
          <div v-else class="card bg-light">
            <div class="card-body py-5 text-center">
              <template v-if="loading">
                <span class="spinner spinner-border"></span>
              </template>
              <template v-else-if="shippingData.shippingId">
                {{ $lang('shopgo.cart.text.no.payments') }}
              </template>
              <template v-else>
                {{ $lang('shopgo.cart.text.select.shipping.first') }}
              </template>
            </div>
          </div>
        </div>

        <!-- Note -->
        <div class="l-checkout-note card mb-4">
          <div class="card-body">
            <h5 class="card-title mb-3">
              {{ $lang('shopgo.cart.field.note') }}
            </h5>

            <textarea rows="4"
              class="form-control"
              v-model="note"
              name="checkout[note]"
              :placeholder="$lang('shopgo.cart.field.note.placeholder')"
            ></textarea>
          </div>
        </div>
      </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4 l-cart-page__sidebar">
      <div class="l-cart-sidebar position-sticky"
        style="top: var(--sidebar-offsets-top, 90px);"
      >
        <div class="card">
          <!-- Code Input -->
          <div class="card-body l-cart-coupons border-bottom">
            <h5>{{ $lang('shopgo.cart.label.discount.code') }}</h5>
            <div class="d-flex gap-2">
              <input type="text" class="form-control" v-model="code" />
              <button type="button" class="btn btn-secondary text-nowrap"
                style="min-width: 100px"
                @click="addCode"
                :disabled="code === '' || loading"
              >
                {{ $lang('shopgo.cart.button.use.discount.code') }}
              </button>
            </div>

            <!-- Coupons -->
            <div v-if="coupons.length" data-cloak class="list-group list-group-flush mt-4">
              <div v-for="coupon of coupons" class="list-group-item border-top d-flex">
                <div>
                  <div>
                    <strong>
                      {{ coupon.title }}
                    </strong>
                  </div>
                  <div class="small text-muted">
                    {{ coupon.code }}
                  </div>
                </div>

                <div class="ms-auto">
                  <a href="javascript://"
                    class="link-secondary"
                    v-tooltip
                    title="{{ $lang('shopgo.cart.button.remove.discount.code') }}"
                    @click="removeCode(coupon.id)">
                    <i class="fa fa-trash"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>

          <!-- Totals Loading -->
          <div v-if="!loaded" class="card-body">
            <div class="card-text placeholder-glow d-flex my-2">
              <span class="placeholder col-4"></span>
              <span class="placeholder col-3 ms-auto"></span>
            </div>
          </div>

          <!-- Totals -->
          <div v-if="loaded" data-cloak class="card-body l-cart-totals text-end">
            <div class="l-cart-total d-flex justify-content-between gap-1 mb-1 w-100">
              <div class="l-cart-total__label">
                {{ $lang('shopgo.cart.label.total') }}
              </div>

              <div v-if="totals.total" class="l-cart-total__value">
                {{ $formatPrice(totals.total.price, { code: true }) }}
              </div>
            </div>

            <div class="l-cart-total d-flex justify-content-between gap-1 mb-1 w-100"
              v-for="total of filteredTotals">
              <div class="l-cart-total__label d-flex gap-2">
                <div>
                  {{ total.label }}
                </div>
                <div
                  v-if="total.params.type === 'coupon' || total.params.subtype === 'code'">
                  <small>({{ total.params.code }})</small>
                </div>
              </div>

              <div class="l-cart-total__value">
                {{ $formatPrice(total.price, { code: true }) }}
              </div>
            </div>
          </div>
        </div>

        <!-- Checkbox -->
        <div class="card mt-3 position-sticky"
          style="bottom: 0;">
          <div class="card-body d-grid gap-3">
            <!-- Grand Total -->
            <div v-if="loaded"
              class="l-cart-total d-flex justify-content-between gap-1 w-100 fs-5 fw-bold"
              data-cloak>
              <div class="l-cart-total__label">
                {{ $lang('shopgo.cart.label.grand.total') }}
              </div>

              <div v-if="totals.grand_total" class="l-cart-total__value text-end">
                <div>
                  {{ $formatPrice(totals.grand_total.price, { code: true }) }}
                </div>
                <div v-if="$currency.isSubCurrency()" class="mt-1 small text-muted fw-normal">
                  ({{ $currency.formatMainCurrency(totals.grand_total.price, { code: true }) }})
                </div>
              </div>
            </div>

            <!-- Shipping / Payment Info -->
            <div v-if="loaded" class="d-flex justify-content-between"
              data-cloak>
              <div>
                <i class="fa fa-truck"></i>
                {{ selectedShipping?.title || $lang('shopgo.message.no.shipping.selected') }}
              </div>

              <div>
                <i class="fa fa-credit-card"></i>
                {{ selectedPayment?.title || $lang('shopgo.message.no.payment.selected') }}
              </div>
            </div>

            <!-- Loading -->
            <div v-if="!loaded">
              <div class="card-text placeholder-glow d-flex mb-1" style="height: 1.25rem;">
                <span class="placeholder col-3"></span>
                <span class="placeholder col-4 ms-auto"></span>
              </div>
            </div>

            <!-- Loading -->
            <div v-if="!loaded">
              <div class="card-text placeholder-glow d-flex">
                <span class="placeholder col-3"></span>
                <span class="placeholder col-3 ms-auto"></span>
              </div>
            </div>

            <!-- Checkout Button -->
            <button type="button" class="btn btn-primary btn-lg"
              :disabled="loading || !canCheckout"
              @click="checkout"
            >
              <div data-cloak>
                <template v-if="loading">
                  <span class="spinner spinner-grow spinner-grow-sm"></span>
                </template>
                <template v-else>
                  {{ $lang('shopgo.cart.button.process.checkout') }}
                </template>
              </div>
              <div v-if="!loading" data-loading>
                <span class="spinner spinner-grow spinner-grow-sm"></span>
              </div>
            </button>
          </div>
        </div>
      </div>
      <!-- End Sidebar-->
    </div>
  </div>
</template>

<style scoped>

</style>

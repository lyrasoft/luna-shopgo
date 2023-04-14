// JS file for Cart

import '@main';

await u.domready();

const { createApp, ref, toRefs, reactive, computed, watch, provide, nextTick, onMounted } = Vue;

const CartApp = {
  name: 'CartApp',
  components: {
    'address-form': addressForm(),
    'shipping-item': shippingItem(),
    'payment-item': paymentItem(),
  },
  props: {
    user: Object,
    checkoutData: Object
  },
  setup(props) {
    const state = reactive({
      loaded: false,
      items: [],
      totals: [],
      coupons: [],
      paymentId: props.checkoutData?.payment?.id || '',
      paymentData: props.checkoutData?.payment_data || {},
      shippingId: props.checkoutData?.shipping?.id || '',
      shippingData: props.checkoutData?.shipping_data || {},
      shippings: [],
      payments: [],
      receiptData: {},
      code: '',
      note: props.checkoutData?.note || '',
      loading: false,
    });

    const form = ref(null);
    const toggleAllInput = ref(null);
    const loadingStack = u.stack('loading');

    loadingStack.observe((stack, length) => {
      state.loading = length > 0;
    });

    init();

    function popLoading(wait = 300) {
      setTimeout(() => {
        loadingStack.pop();
      }, wait);
    }

    const afterItemsChanged = u.debounce(function () {
      return loadItems();
    }, 300);

    async function loadItems(updateShippings = true) {
      loadingStack.push(true);

      try {
        const res = await u.$http.get(
          '@cart_ajax/getItems',
          {
            params: {
              location_id: state.shippingData.location_id,
              shipping_id: state.shippingId,
              payment_id: state.paymentId,
            }
          }
        );

        await setCartData(res.data.data, updateShippings);

        return res;
      } catch (e) {
        console.error(e);
        u.alert(e.message, '', 'warning');
      } finally {
        popLoading();
      }
    }

    async function setCartData(data, updateShippings = true) {
      state.items = data.items;
      state.totals = data.totals;
      state.coupons = data.coupons;

      if (updateShippings) {
        return await loadShippings();
      }

      return;
    }

    // Toggle checks
    watch(() => state.items, () => {
      updateToggleAll(state.items);
    }, { deep: true });

    const itemChecks = computed(() => {
      return state.items.map((item) => {
        if (item.options.checked == null) {
          return true;
        }

        return item.options.checked;
      });
    });

    const checks = computed(() => itemChecks.value.filter(checked => checked === true).length);
    const unchecks = computed(() => itemChecks.value.filter(checked => checked === false).length);

    function updateToggleAll() {
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
      for (const item of state.items) {
        item.options.checked = toggleAllInput.value.checked;
      }

      updateChecks();
    }

    const updateChecks = u.debounce(async (item) => {
      const checks = {};

      for (const item of state.items) {
        checks[item.key] = item.options.checked ? '1' : '0';
      }

      loadingStack.push(true);

      try {
        const res = await u.$http.post('@cart_ajax/updateChecks', { checks });

        return await loadItems();
      } catch (e) {
        console.error(e);
        u.alert(e.message, '', 'warning');
      } finally {
        popLoading();
      }
    }, 300);

    onMounted(() => {
      calcNavAndStickySidebar(form.value);
    });

    async function init() {
      await loadItems();

      state.loaded = true;
    }

    // Actions
    async function removeItem(item, i) {
      loadingStack.push(true);

      try {
        const res = await u.$http.delete(`@cart_ajax/removeItem?key=${item.key}`);

        return await afterItemsChanged();
      } catch (e) {
        console.error(e);
        u.alert(e.message, '', 'warning');
      } finally {
        loadingStack.pop();
      }
    }

    async function clearCart() {
      loadingStack.push(true);

      try {
        await u.$http.put(`@cart_ajax/clearCart`);

        await loadItems();

        await u.alert('已移除所有商品', '將回到首頁', 'success');

        location.href = u.route('home');
      } catch (e) {
        console.error(e);
        u.alert(e.message, '', 'warning');
      } finally {
        loadingStack.pop();
      }
    }

    // Quantity
    async function changeItemQuantity(item, offsets) {
      item.quantity += offsets;

      item.quantity = Math.max(item.quantity, 1);

      await updateQuantities(item);
    }

    const updateQuantities = u.debounce(async (item) => {
      item.quantity = Math.max(item.quantity, 1);

      const values = {};

      for (const item of state.items) {
        values[item.key] = item.quantity;
      }

      loadingStack.push(true);

      try {
        const res = await u.$http.post('@cart_ajax/updateQuantities', { values });

        return await loadItems();
      } catch (e) {
        console.error(e);
        u.alert(e.message, '', 'warning');
      } finally {
        popLoading();
      }
    }, 300);

    // Code / Coupons
    async function addCode() {
      if (state.code === '') {
        return;
      }

      loadingStack.push(true);

      try {
        const res = await u.$http.post('@cart_ajax/addCode', { code: state.code });

        state.code = '';

        await loadItems();
      } catch (e) {
        console.error(e);
        u.alert(e.message, '', 'warning');
      } finally {
        popLoading();
      }
    }

    async function removeCode(id) {
      loadingStack.push(true);

      try {
        const res = await u.$http.delete('@cart_ajax/removeCode', { id });

        await loadItems();
      } catch (e) {
        console.error(e);
        u.alert(e.message, '', 'warning');
      } finally {
        popLoading();
      }
    }

    // Totals
    const filteredTotals = computed(() => {
      const totals = [];

      for (const name in state.totals) {
        if (name === 'total') {
          continue;
        }

        if (name === 'grand_total') {
          continue;
        }

        const total = state.totals[name];

        if (Number(total.price) === 0) {
          continue;
        }

        totals.push(total);
      }

      return totals;
    });

    // Shippings
    watch(() => state.shippingData.location_id, () => {
      loadShippings();
    });
    watch(() => state.shippingId, () => {
      loadItems(false);
    });

    const selectedShipping = computed(() => {
      return state.shippings.find(item => item.id === state.shippingId);
    });

    const loadShippings = u.debounce(async function() {
      loadingStack.push(true);

      try {
        const res = await u.$http.get(`@cart_ajax/shippings?location_id=${state.shippingData.location_id}`);

        state.shippings = res.data.data;

        await nextTick();
        await nextTick();
        
        if (state.shippings.length > 0) {
          if (!selectedShipping.value) {
            state.shippingId = state.shippings[0].id;
          }
        } else {
          state.shippingId = null;
        }
      } catch (e) {
        console.error(e);
        u.alert(e.message, '', 'warning');
      } finally {
        popLoading();
      }
    }, 300);

    // Payments
    watch(() => [state.shippingData.location_id, state.shippingId], () => {
      loadPayments();
    });

    const selectedPayment = computed(() => {
      return state.payments.find(item => item.id === state.paymentId);
    });

    const loadPayments = u.debounce(async function () {
      loadingStack.push(true);

      try {
        const res = await u.$http.get(
          `@cart_ajax/payments`,
          {
            params: {
              location_id: state.shippingData.location_id,
              shipping_id: state.shippingId
            }
          }
        );

        state.payments = res.data.data;

        await nextTick();
        await nextTick();

        if (state.payments.length > 0) {
          if (!state.payments.find((payment) => payment.id === state.paymentId)) {
            state.paymentId = state.payments[0].id;
          }
        } else {
          state.paymentId = null;
        }
      } catch (e) {
        console.error(e);
        u.alert(e.message, '', 'warning');
      } finally {
        popLoading();
      }
    }, 300);

    // Checkout
    const canCheckout = computed(() => {
      if (checks.value === 0) {
        return false;
      }

      if (!state.shippingData.location_id) {
        return false;
      }

      if (!state.paymentData.location_id) {
        return false;
      }

      if (!state.shippingId) {
        return false;
      }

      if (!state.paymentId) {
        return false;
      }

      return true;
    });

    const shippingForm = ref(null);
    const paymentForm = ref(null);

    function checkout() {
      if (checks.value === 0) {
        return;
      }

      if (shippingForm.value && !shippingForm.value.validate()) {
        console.log('Shipping Validate Fail');
        return;
      }

      if (paymentForm.value && !paymentForm.value.validate()) {
        console.log('Payment Validate Fail');
        return;
      }

      if (!form.value.checkValidity()) {
        form.value.reportValidity();

        const invalid = form.value.querySelector(':invalid');

        if (invalid && !isVisible(invalid) && invalid.dataset.validationMessage) {
          u.alert(invalid.dataset.validationMessage);
        }

        return;;
      }

      state.loading = true;

      form.value.requestSubmit();
    }

    function isVisible(el) {
      return !!(el.offsetWidth || el.offsetHeight || el.getClientRects().length);
    }

    return {
      ...toRefs(state),
      filteredTotals,
      form,
      toggleAllInput,
      canCheckout,
      selectedShipping,
      selectedPayment,
      shippingForm,
      paymentForm,

      removeItem,
      clearCart,
      changeItemQuantity,
      addCode,
      removeCode,
      updateQuantities,
      updateChecks,
      toggleChecked,
      checkout,
    };
  }
};

const app = createApp(CartApp, u.data('cart.props'));

app.use(ShopGoVuePlugin);
app.directive('tooltip', ShopGoVuePlugin.Tooltip);
app.directive('tom-select', ShopGoVuePlugin.TomSelect);
app.mount('cart-app');

function calcNavAndStickySidebar(form, offsets = 30) {
  const navbar = document.querySelector('header .navbar, .navbar');

  if (!navbar) {
    return;
  }

  const top = navbar.clientHeight + offsets;

  form.style.setProperty('--sidebar-offsets-top', top + 'px');
}

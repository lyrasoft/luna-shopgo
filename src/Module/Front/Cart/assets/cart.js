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
    user: Object
  },
  setup(props) {
    const state = reactive({
      loaded: false,
      items: [],
      totals: [],
      coupons: [],
      paymentId: '',
      paymentData: {},
      shippingId: '',
      shippingData: {},
      shippings: [],
      payments: [],
      code: '',
      note: '',
      loading: false,
    });

    const form = ref(null);
    const loadingStack = u.stack('loading');

    loadingStack.observe((stack, length) => {
      state.loading = length > 0;
    });

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
              location_id: state.shippingData.locationId,
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

    init();

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
    watch(() => state.shippingData.locationId, () => {
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
        const res = await u.$http.get(`@cart_ajax/shippings?location_id=${state.shippingData.locationId}`);

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
    watch(() => [state.shippingData.locationId, state.shippingId], () => {
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
              location_id: state.shippingData.locationId,
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
      if (!state.shippingData.locationId) {
        return false;
      }

      if (!state.paymentData.locationId) {
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
      if (!shippingForm.value.validate()) {
        console.log('Shipping Validate Fail');
        return;
      }

      if (!paymentForm.value.validate()) {
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

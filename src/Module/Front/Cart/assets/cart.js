// JS file for Cart

import '@main';

await u.domready();

const { createApp, ref, toRefs, reactive, computed, watch, provide, nextTick, onMounted } = Vue;

const CartApp = {
  name: 'CartApp',
  components: {
    'address-form': addressForm(),
    'shipping-item': shippingItem(),
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
      loading: false
    });

    const form = ref(null);

    const loadItems = u.debounce(async function () {
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

      setCartData(res.data.data);
    }, 300);

    function setCartData(data) {
      state.items = data.items;
      state.totals = data.totals;

      loadShippings();
    }

    init();

    async function init() {
      await loadItems();

      state.loaded = true;
    }

    // Actions
    async function removeItem(item, i) {
      const res = await u.$http.delete(`@cart_ajax/removeItem?key=${item.key}`);

      return loadItems();
    }

    // Quantity
    function changeItemQuantity(item, offsets) {
      item.quantity += offsets;

      updateQuantities();
    }

    const updateQuantities = u.debounce(async () => {
      const values = {};

      for (const item of state.items) {
        values[item.key] = item.quantity;
      }

      try {
        const res = await u.$http.post('@cart_ajax/updateQuantities', { values });

        return loadItems();
      } catch (e) {
        u.alert(e.message, '', 'warning');
      }
    }, 300);

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
      loadItems();
    });

    const loadShippings = u.debounce(async function () {
      const res = await u.$http.get(`@cart_ajax/shippings?location_id=${state.shippingData.locationId}`);

      state.shippings = res.data.data;
    }, 300);

    // Payments

    return {
      ...toRefs(state),
      filteredTotals,
      form,

      removeItem,
      changeItemQuantity,
      updateQuantities,
    };
  }
};

const app = createApp(CartApp, u.data('cart.props'));

app.use(ShopGoVuePlugin);
app.directive('tom-select', ShopGoVuePlugin.TomSelect);
app.mount('cart-app');

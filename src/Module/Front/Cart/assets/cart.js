// JS file for Cart

import '@main';

await u.domready();

const { createApp, ref, toRefs, reactive, computed, watch, provide, nextTick, onMounted } = Vue;

const CartApp = {
  name: 'CartApp',
  components: {
    'address-form': addressForm(),
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

    init();

    async function init() {
      await loadItems();

      state.loaded = true;
    }

    async function loadItems() {
      const res = await u.$http.get('@cart_ajax/getItems');

      setCartData(res.data.data);
    }

    function setCartData(data) {
      state.items = data.items;
      state.totals = data.totals;
    }

    // Actions
    async function removeItem(item, i) {
      const res = await u.$http.delete(`@cart_ajax/removeItem?key=${item.key}`);

      setCartData(res.data.data);
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

        setCartData(res.data.data);
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
    watch(() => state.shippingData.locationId, (locId) => {
      loadShippings(locId);
    });

    async function loadShippings(locId) {
      const res = await u.$http.get(`@cart_ajax/shippings?location_id=${locId}`);

      state.shippings = res.data.data;
    }

    // Payments

    return {
      ...toRefs(state),
      filteredTotals,

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

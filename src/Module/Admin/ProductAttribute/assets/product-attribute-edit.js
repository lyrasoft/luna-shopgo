/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

import '@main';

u.$ui.bootstrap.tooltip();
u.$ui.tomSelect('.has-tom-select');

const formSelector = '#admin-form';

// Validation
u.formValidation().then(() => {
  u.$ui.disableOnSubmit(formSelector);
});

// Init form
u.form(formSelector).initComponent();

// Disable if uploading
u.$ui.disableIfStackNotEmpty();

// Keep Alive
u.$ui.keepAlive(location.href);

// A workaround to wait all dependencies ready
await u.domready();

// App
const $typeSelect = document.querySelector('#input-item-type');
const { ref, onMounted, computed, createApp, toRefs, reactive } = Vue;

const app = createApp({
  name: 'ProductAttributeApp',
  setup() {
    const items = ShopgoVueUtilities.prepareVueItemList(
      u.data('options') || [],
      (item) => {
        item.uid = item.uid = u.tid();
        return {
          data: item,
          uid: item.uid,
          selected: false
        };
      }
    );

    const type = ref($typeSelect.value);
    const state = reactive({
      items,
      current: null,
      selected: [],
      defaultUid: items.find((item) => item.data.is_default)?.uid
    });

    onMounted(() => {
      $typeSelect.addEventListener('change', () => {
        type.value = $typeSelect.value;
      });
    });

    function selectItem(item) {
      state.current = item;
    }

    function addNewItem(item = null) {
      const i = item ? state.items.indexOf(item) + 1 : state.items.length;

      state.items.splice(
        i,
        0,
        ShopgoVueUtilities.prepareVueItem(
          {
            value: '',
            text: '',
            is_default: false
          },
          (data) => {
            data.uid = data.uid = u.tid();
            return {
              data: data,
              uid: data.uid,
              selected: false
            };
          }
        )
      );
    }

    function removeItem(item) {
      const i = state.items.indexOf(item);

      state.items.splice(i, 1);
    }

    function removeItems() {
      state.items = state.items.filter((item) => !state.selected.includes(item.uid));

      if (state.selected.includes(state.current?.uid)) {
        state.current = null;
      }

      state.selected = [];
    }

    function toJson(data) {
      return JSON.stringify(data);
    }

    return {
      type,
      ...toRefs(state),

      selectItem,
      addNewItem,
      removeItem,
      removeItems,
      toJson,
    };
  }
});

app.use(ShopGoVuePlugin);
app.component('draggable', vuedraggable);
app.mount('#product-attribute-app');

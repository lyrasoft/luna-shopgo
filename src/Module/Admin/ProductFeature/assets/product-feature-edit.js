/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

import '@main';

u.$ui.bootstrap.tooltip();

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

// App
const $typeSelect = document.querySelector('#input-item-type');
const { ref, onMounted, computed, createApp, toRefs, reactive } = Vue;

const app = createApp({
  name: 'ProductFeatureApp',
  setup() {
    const type = ref($typeSelect.value);
    const state = reactive({
      items: ShopGoUtilities.prepareVueItemList(
        u.data('options') || [],
        (item) => {
          return {
            data: item,
            selected: false
          };
        }
      ),
      current: null,
      selected: [],
    })

    onMounted(() => {
      $typeSelect.addEventListener('change', () => {
        type.value = $typeSelect.value;
      });
    });

    function selectItem(item) {

    }

    function addNewItem() {

    }

    function removeItems() {

    }

    function toJson(data) {
      return JSON.stringify(data);
    }

    return {
      ...toRefs(state),

      selectItem,
      addNewItem,
      removeItems,
      toJson,
    };
  }
});

app.use(ShopGoAppPlugin);
app.component('draggable', vuedraggable);
app.mount('#product-feature-app');

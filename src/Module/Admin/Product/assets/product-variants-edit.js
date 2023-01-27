/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

import '@main';
import '@unicorn/vue/vue-drag-uploader.js';

u.$ui.flatpickr();

// A workaround to wait all dependencies ready
await u.domready();

const { createApp, ref, toRefs, reactive, computed, watch, provide, nextTick } = Vue;

const priceInput = document.querySelector('#input-item-variant-price');
const form = document.querySelector('#admin-form');

const app = createApp(
  {
    name: 'ProductVariantsEditApp',
    components: {
      VariantListItem: variantListItem(),
      VariantInfoEdit: variantInfoEdit(),
      VariantGeneration: variantGeneration(),
    },
    props: {
      product: Object,
      variants: Array
    },
    setup(props) {
      const state = reactive({
        items: prepareItems(props.variants),
        generate: {
          edit: false,
        },
        lastCheckItemIndex: 0,
      });

      const mainPrice = ref(priceInput.value);

      provide('product', props.product || {});
      provide('mainPrice', mainPrice);

      priceInput.addEventListener('change', () => {
        mainPrice.value = parseFloat(priceInput.value);
      });

      // Unsave
      let formSubmitting = false;
      const initialHash = u.md5(JSON.stringify(state.items));
      const saveRequired = computed(() => u.md5(itemsJSON.value) !== initialHash);

      window.addEventListener('beforeunload', (e) => {
        if (saveRequired.value && !formSubmitting) {
          e.preventDefault();
          e.stopPropagation();
          e.returnValue = 'Save Required';
        }
      });

      form.addEventListener('submit', () => {
        formSubmitting = true;
      });

      const checkedItems = computed(() => {
        return state.items.filter((item) => item.checked);
      });

      function prepareItems(items) {
        return ShopgoVueUtilities.prepareVueItemList(
          items,
          (item) => {
            item.checked = false;
            item.unsave = false;
          }
        );
      }

      function checkAll($event, value = null) {
        state.items.forEach(item => {
          item.checked = value === null ? $event.target.checked : value;
        });
      }

      async function multiCheck($event, i) {
        if (!await confirmLeave()) {
          $event.preventDefault();
          $event.target.checked = false;
          state.items[i].checked = false;
          return;
        }

        state.items[i].checked = true;

        if (state.lastCheckItemIndex === null) {
          state.lastCheckItemIndex = i;
          return;
        }

        if ($event.shiftKey) {
          let k = state.lastCheckItemIndex;

          if (state.lastCheckItemIndex < i) {
            for (; k < i; k++) {
              state.items[k].checked = $event.target.checked;
            }
          } else {
            for (; k > i; k--) {
              state.items[k].checked = $event.target.checked;
            }
          }
        }

        state.lastCheckItemIndex = i;
      }

      function countChecked() {
        return checkedItems.value.length;
      }

      // Editing
      const current = computed(() => {
        if (checkedItems.value.length === 1) {
          return checkedItems.value[0];
        }

        return {};
      });
      const variantEdit = ref(null);

      async function editVariant(item) {
        if (!await cancelEdit()) {
          return;
        }

        checkAll(null, false);

        state.generate.edit = false;
        item.checked = true;
      }

      async function generateCombinations() {
        if (!await cancelEdit()) {
          return;
        }

        state.generate.edit = true;
      }

      function generated(variants) {
        state.items = state.items.concat(prepareItems(variants));

        state.generate.edit = false;
      }

      async function cancelEdit() {
        if (variantEdit.value) {
          if (variantEdit.value.unsave) {
            if (await confirmLeave()) {
              return false;
            }
          }

          checkAll(null, false);
        }

        return true;
      }

      async function confirmLeave() {
        if (variantEdit.value) {
          if (variantEdit.value.unsave) {
            const v = await u.confirm('尚未儲存，您確定要離開嗎？');

            if (!v) {
              return false;
            }
          }
        }

        return true;
      }

      function deleteVariants(item = null) {
        if (!item) {
          state.items = state.items.filter(it => !it.checked);
        } else {
          state.items = state.items.filter(it => it.hash !== item.hash);
        }
      }

      // Input
      const itemsJSON = computed(() => JSON.stringify(state.items));

      return {
        ...toRefs(state),
        checkedItems,
        variantEdit,
        current,
        itemsJSON,

        checkAll,
        multiCheck,
        editVariant,
        deleteVariants,
        generated,
        generateCombinations,
        countChecked,
        cancelEdit,
      }
    }
  },
  u.data('product.variants.props')
);

app.use(ShopGoVuePlugin);
app.component('draggable', vuedraggable);
app.mount('product-variants-edit-app');

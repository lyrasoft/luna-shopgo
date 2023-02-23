/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

import '@main';

u.$ui.flatpickr();

// A workaround to wait all dependencies ready
await u.domready();

const { createApp, ref, toRefs, reactive, computed, watch, provide, nextTick } = Vue;

const priceInput = document.querySelector('#input-item-variant-price');
const form = document.querySelector('#admin-form');

const dateFormat = 'Y-m-d H:i:S';

const app = createApp(
  {
    name: 'ProductDiscountsEditApp',
    props: {
      product: Object,
      discounts: Array
    },
    setup(props) {
      const state = reactive({
        items: prepareItems(props.discounts),
        current: {},
        currentIndex: -1,
        currentHash: '',
        lastCheckItemIndex: null,
        flatpickrOptions: JSON.stringify(
          {
            dateFormat,
            enableTime: true,
            enableSeconds: true,
            allowInput: true,
            time_24hr: true,
            // wrap: true,
            monthSelect: false,
          }
        ),
        inputStep: u.data('input.step') || '0.0001',
      });

      const mainPrice = ref(priceInput.value);

      priceInput.addEventListener('change', () => {
        mainPrice.value = parseFloat(priceInput.value);
      });

      // Input
      const itemsJSON = computed(() => JSON.stringify(state.items));
      const currentItemsHash = computed(() => u.md5(itemsJSON.value));

      function prepareItems(items) {
        return ShopgoVueUtilities.prepareVueItemList(
          items,
          (item) => {
            item.checked = false;
            item.unsave = false;
          }
        )
      }

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

      function checkAll($event) {
        state.items.forEach(item => {
          item.checked = $event.target.checked;
        });
      }

      function multiCheck($event, item, i) {
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

      function newItem() {
        const item = {
          id: null,
          productId: props.product.id,
          type: 'product',
          subtype: 'discount',
          minProductQuantity: 0,
          price: '',
          start_date: null,
          end_date: null,
          method: 'offsets',
          state: 1
        };

        prepareItems([item]);

        state.items.push(item);

        editItem(item, state.items.length - 1);
      }

      const currentEditUnsave = computed(() => state.currentCopy !== JSON.stringify(state.current));

      watch(() => state.current.method, (method) => {
        if (state.currentIndex === -1) {
          return;
        }

        if (['percentage', 'fixed'].indexOf(method) !== -1) {
          state.current.price = Math.abs(state.current.price);
        } else {
          state.current.price = -Math.abs(state.current.price);
        }
      });

      async function editItem(item, i) {
        item.publishUp = dateToSQLFormat(item.publishUp);
        item.publishDown = dateToSQLFormat(item.publishDown);

        state.currentHash = hashItem(item);

        state.current = item;
        state.currentIndex = i;
      }

      function hashItem(item) {
        const newItem ={ ...item };

        delete newItem.checked;
        delete newItem.unsave;

        return u.md5(JSON.stringify(newItem));
      }

      watch(() => state.current, () => {
        if (
          state.currentHash !== ''
          && state.currentHash !== hashItem(state.current)
        ) {
          state.items[state.currentIndex].unsave = true;
        }
      }, { deep: true });

      function dateToSQLFormat(dateStr) {
        if (!dateStr) {
          return dateStr;
        }

        return flatpickr.formatDate(flatpickr.parseDate(dateStr), dateFormat);
      }

      function cancelEdit() {
        state.currentHash = '';
        state.current = {};
        state.currentIndex = -1;
      }

      function deleteItems(item = null) {
        if (!item) {
          state.items = state.items.filter(function (it) {
            if (it.checked && it.uid === state.current.uid) {
              cancelEdit();
            }

            return !it.checked;
          });
        } else {
          if (item.uid === state.current.uid) {
            cancelEdit();
          }

          state.items = state.items.filter(it => it.uid !== item.uid);
        }
      }

      function reorder() {
        state.items.forEach((item, i) => {
          item.ordering = i + 1;
        });
      }

      function timeLimit(item) {
        let text = '';

        if (item.publishUp) {
          text += new Date(item.publishUp).toLocaleString(undefined, { timeZone: 'UTC' })
        } else {
          text += '現在'
        }

        text += ' 到 ';

        if (item.publishDown) {
          text += new Date(item.publishDown).toLocaleString(undefined, { timeZone: 'UTC' })
        } else {
          text += '不限期'
        }

        return text;
      }

      function correctPriceInput() {
        if (state.current.method === 'fixed') {
          state.current.price = Math.max(state.current.price, 0);
        } else if (state.current.method === 'offsets') {
          state.current.price = Math.min(state.current.price, 0);
        } else {
          state.current.price = Math.max(state.current.price, 0);
          state.current.price = Math.min(state.current.price, 100);
        }
      }

      return {
        ...toRefs(state),
        checkedItems,
        itemsJSON,
        saveRequired,
        currentEditUnsave,

        checkAll,
        countChecked,
        multiCheck,
        newItem,
        editItem,
        cancelEdit,
        deleteItems,
        reorder,
        timeLimit,
        correctPriceInput,
      }
    }
  },
  u.data('product.discounts.props')
);

app.use(ShopGoVuePlugin);
app.component('draggable', vuedraggable);
app.directive('tooltip', ShopGoVuePlugin.Tooltip);
app.mount('product-discounts-edit-app');

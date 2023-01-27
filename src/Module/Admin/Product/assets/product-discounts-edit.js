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
        currentCopy: '',
        lastCheckItemIndex: null,
        flatpickrOptions: JSON.stringify(
          {
            dateFormat: 'Y-m-d H:i:S',
            enableTime: true,
            enableSeconds: true,
            allowInput: true,
            time_24hr: true,
            // wrap: true,
            monthSelect: false,
          }
        ),
      });

      const mainPrice = ref(priceInput.value);

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
          method: 'offset',
          state: 1
        };

        prepareItems([item]);

        state.items.push(item);

        editItem(item, state.items.length - 1);
      }

      const currentEditUnsave = computed(() => state.currentCopy !== JSON.stringify(state.current));

      let unwatchMethodFunc = null;

      function unwatchMethod() {
        if (unwatchMethodFunc) {
          unwatchMethodFunc();

          unwatchMethodFunc = null;
        }
      }

      async function editItem(item, i) {
        await cancelEdit();

        state.currentIndex = i;
        state.currentCopy = JSON.stringify(item);
        state.current = JSON.parse(state.currentCopy);

        unwatchMethodFunc = watch(() => state.current.method, (method) => {
          if (['percentage', 'fixed'].indexOf(method) !== -1) {
            state.current.price = Math.abs(state.current.price);
          } else {
            state.current.price = -Math.abs(state.current.price);
          }
        });
      }

      async function cancelEdit() {
        if (!await confirmLeave()) {
          return;
        }

        unwatchMethod();

        state.currentIndex = -1;
        state.current = {};
        state.currentCopy = '';
      }

      async function confirmLeave() {
        if (state.current.id && currentEditUnsave.value) {
          const v = await u.confirm(
            '目前編輯的尚未儲存，確定要取消嗎？'
          );

          if (!v) {
            return false;
          }
        }

        return true;
      }

      function saveItem() {
        const i = state.currentIndex;

        state.items[i] = Object.assign(state.items[i], state.current);
        state.items[i].unsave = true;

        state.currentCopy = JSON.stringify(state.current);
      }

      function deleteItems(item = null) {
        if (!item) {
          state.items = state.items.filter(it => !it.checked);
        } else {
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

      return {
        ...toRefs(state),
        checkedItems,
        itemsJSON,
        currentItemsHash,
        saveRequired,
        currentEditUnsave,

        checkAll,
        countChecked,
        multiCheck,
        newItem,
        editItem,
        cancelEdit,
        saveItem,
        deleteItems,
        reorder,
        timeLimit,

      }
    }
  },
  u.data('product.discounts.props')
);

app.use(ShopGoVuePlugin);
app.component('draggable', vuedraggable);
app.directive('tooltip', ShopGoVuePlugin.Tooltip);
app.mount('product-discounts-edit-app');

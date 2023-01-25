/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

import '@main';
import '@unicorn/vue/vue-drag-uploader.js';

const { createApp, ref, toRefs, reactive, computed, watch } = Vue;

const app = createApp(
  {
    name: 'ProductVariantsEditApp',
    components: {
      VariantListItem: variantListItem(),
      VariantInfoEdit: variantInfoEdit()
    },
    props: {
      variants: Array
    },
    setup(props) {
      const state = reactive({
        items: ShopgoVueUtilities.prepareVueItemList(
          props.variants,
          (item) => {
            item.checked = false;
            item.unsave = false;
          }
        ),
        generate: {
          edit: false,
        },
        current: null,
        saveRequired: false,
        lastCheckItemIndex: 0,
      });

      const checkedItems = computed(() => {
        return state.items.filter((item) => item.checked);
      });

      function checkAll($event, value = null) {
        state.items.forEach(item => {
          item.checked = value === null ? $event.target.checked : value;
        });
      }

      function multiCheck($event, i) {
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

      function editVariant(item) {
        checkAll(null, false);

        state.generate.edit = false;
        item.checked = true;
        state.current = item;

        // this.$options.currentCopy = JSON.parse(JSON.stringify(item));
      }

      function generateCombinations() {

      }

      function deleteVariants() {

      }

      return {
        ...toRefs(state),
        checkedItems,

        checkAll,
        multiCheck,
        editVariant,
        deleteVariants,
        generateCombinations,
        countChecked,
      }
    }
  },
  u.data('product.variants.props')
);

// A workaround to wait all dependencies ready
await u.domready();

app.use(ShopGoVuePlugin);
app.component('draggable', vuedraggable);
app.mount('product-variants-edit-app');

function variantListItem() {
  return {
    name: 'VariantListItem',
    template: u.selectOne('#c-variant-list-item').innerHTML,
    props: {
      item: Object,
      i: Number,
      active: Boolean,
    },
    setup(props, { emit }) {


      function changeStock() {

      }

      function edit() {
        emit('edit', props.item);
      }

      function remove() {

      }

      function multiCheck($event) {
        emit('oncheck', $event, props.i);
      }

      return {
        multiCheck,
        changeStock,
        edit,
        remove,
      }
    }
  }
}

function variantInfoEdit() {
  return {
    name: 'VariantInfoEdit',
    template: u.selectOne('#c-variant-info-edit').innerHTML,
    props: {
      variants: Array,
    },
    setup(props) {
      const state = reactive({
        current: {},
        items: []
      });

      watch(() => props.variants, () => {
        state.current = {
          price: '',
          weight: '',
        };
        state.items = JSON.parse(JSON.stringify(props.variants));

        if (state.items.length === 1) {
          state.current = state.items[0];
        }
      }, { immediate: true });

      function save() {

      }

      function cancelEdit() {

      }

      return {
        ...toRefs(state),

        save,
        cancelEdit,
      }
    }
  }
}

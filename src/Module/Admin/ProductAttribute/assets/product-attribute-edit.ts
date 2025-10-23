import { tid } from '@lyrasoft/ts-toolkit/generic';
import {
  data,
  useBs5Tooltip,
  useDisableIfStackNotEmpty,
  useDisableOnSubmit,
  useFormComponent,
  useFormValidation,
  useKeepAlive,
  useTomSelect,
} from '@windwalker-io/unicorn-next';
import { createApp, onMounted, reactive, ref, toRefs } from 'vue';
import { uniqueItemList } from '@lyrasoft/ts-toolkit/vue';

const formSelector = '#admin-form';

useBs5Tooltip();

useFormComponent(formSelector);

useFormValidation().then(() => useDisableOnSubmit(formSelector));

useDisableIfStackNotEmpty();

useKeepAlive(location.href);

useTomSelect('.has-tom-select');

// App
const $typeSelect = document.querySelector('#input-item-type');

const app = createApp({
  name: 'ProductAttributeApp',
  setup() {
    const items = uniqueItemList(
      data('options') || [],
      (item) => {
        item.uid = item.uid = tid();
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

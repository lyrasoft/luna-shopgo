import {
  __, data,
  route,
  simpleAlert,
  useBs5Tooltip,
  useDisableIfStackNotEmpty,
  useDisableOnSubmit,
  useFormComponent,
  useFormValidation, useHttpClient, useIframeModal,
  useKeepAlive, useTomSelect,
} from '@windwalker-io/unicorn-next';

const formSelector = '#admin-form';

useBs5Tooltip();

useFormComponent(formSelector);

useFormValidation().then(() => useDisableOnSubmit(formSelector));

useDisableIfStackNotEmpty();

useKeepAlive(location.href);

useTomSelect('.has-tom-select');

import { ref, onMounted, computed, createApp, toRefs, reactive, defineComponent } from 'vue';

// Todo: Fix this
const AdditionalPurchaseAttachments = defineComponent({
  name: 'AdditionalPurchaseAttachments',
  components: {
    'attachment-product': attachmentProduct()
  },
  props: {
    attachmentData: Array
  },
  setup(props) {
    const state = reactive({
      attachmentSet: ShopgoVueUtilities.prepareVueItemList(
        props.attachmentData,
        (item) => {
          item.open = false;
        }
      )
    });

    if (state.attachmentSet.length === 1) {
      state.attachmentSet[0].open = true;
    }

    useIframeModal();

    onMounted(() => {
      setTimeout(() => {
        const targetSelected = window.targetSelected;

        window.targetSelected = function (value) {
          const id = value.value;

          try {
            checkAvailable(id)
          } catch (e) {
            simpleAlert((e as Error).message);
            return;
          }

          targetSelected(value);
        }
      }, 500);
    });

    const productSelector = ref(null);

    function openProductSelector() {
      const callbackName = 'productSelected';
      const url = new URL(route('product_modal'));
      url.searchParams.set('callback', callbackName);

      window[callbackName] = async function ({ title, value: id, image: cover }) {
        try {
          checkAvailable(id);
        } catch (e) {
          simpleAlert((e as Error).message, '', 'warning');
          return;
        }

        const { get } = useHttpClient();

        const res = await get(`@additional_purchase_ajax/getProductInfo?id=${id}`);

        for (const attachment of state.attachmentSet) {
          attachment.open = false;
        }

        state.attachmentSet.unshift(
          ShopgoVueUtilities.prepareVueItem(
            res.data.data,
            (item) => {
              item.open = true;
            }
          )
        );

        productSelector.value.close();
      }

      productSelector.value.open(url, { size: 'modal-xl' });
    }

    function checkAvailable(id: string | number) {
      // Check is in attachments
      for (const { product } of state.attachmentSet) {
        if (Number(product.id) === Number(id)) {
          throw new Error(__('shopgo.additional.purchase.message.already.selected'));
          return;
        }
      }

      // Check is in targets
      for (const target of document.querySelectorAll<HTMLElement>('#input-item-products-wrap .list-group-item')) {
        if (Number(target.dataset.value) === Number(id)) {
          throw new Error(__('shopgo.additional.purchase.message.already.in.targets'));
          return;
        }
      }
    }

    function removeProduct(i: number) {
      state.attachmentSet.splice(i, 1);
    }

    return {
      ...toRefs(state),
      productSelector,

      openProductSelector,
      removeProduct,
    };
  }
});

const app = createApp(
  AdditionalPurchaseAttachments,
  data('ap.attachments.props')
);

app.use(ShopGoVuePlugin);
app.mount('additional-purchase-attachments-app');

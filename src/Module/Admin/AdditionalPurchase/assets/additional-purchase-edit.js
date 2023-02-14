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

await u.domready();

const { ref, onMounted, computed, createApp, toRefs, reactive } = Vue;

const AdditionalPurchaseAttachments = {
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

    u.$ui.iframeModal();

    const productSelector = ref(null);

    function openProductSelector() {
      const callbackName = 'productSelected';
      const url = new URL(u.route('product_modal'));
      url.searchParams.set('callback', callbackName);

      window[callbackName] = async function ({ title, value: id, image: cover }) {
        for (const { product } of state.attachmentSet) {
          if (Number(product.id) === Number(id)) {
            u.alert(u.__('shopgo.additional.purchase.message.already.selected'));
            return;
          }
        }

        const res = await u.$http.get(`@additional_purchase_ajax/getProductInfo?id=${id}`);

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

    function removeProduct(i) {
      state.attachmentSet.splice(i, 1);
    }

    return {
      ...toRefs(state),
      productSelector,

      openProductSelector,
      removeProduct,
    };
  }
};

const app = createApp(
  AdditionalPurchaseAttachments,
  u.data('ap.attachments.props')
);

app.use(ShopGoVuePlugin);
app.mount('additional-purchase-attachments-app');

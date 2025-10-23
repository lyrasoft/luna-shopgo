<script setup lang="ts">
import { __, route, simpleAlert, useHttpClient, useIframeModal } from '@windwalker-io/unicorn-next';
import { onMounted, reactive, ref, toRefs } from 'vue';
import { uniqueItemList, uniqueItem } from '@lyrasoft/ts-toolkit/vue';
import AttachmentProduct from '~shopgo/components/additional-purchase/AttachmentProduct.vue';
import { Product, ProductVariant } from '~shopgo/types';

interface AttachmentDataItem {
  product: Product;
  variants: Array<ProductVariant>;
  open?: boolean;
}

const props = defineProps<{ attachmentData: AttachmentDataItem[] }>();

const attachmentSet = ref<AttachmentDataItem[]>(
  uniqueItemList(props.attachmentData).map((item) => {
    item.open = false;
    return item;
  })
);

if (attachmentSet.value.length === 1) {
  attachmentSet.value[0].open = true;
}

useIframeModal();

onMounted(() => {
  setTimeout(() => {
    const targetSelected = (window as any).targetSelected;

    (window as any).targetSelected = function (value: any) {
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

const productSelector = ref<any>(null);

function openProductSelector() {
  const callbackName = 'productSelected';
  const url = new URL(route('product_modal'));
  url.searchParams.set('callback', callbackName);

  (window as any)[callbackName] = async function ({ title, value: id, image: cover }: any) {
    try {
      checkAvailable(id);
    } catch (e) {
      simpleAlert((e as Error).message, '', 'warning');
      return;
    }

    const { get } = await useHttpClient();

    const res = await get(`@additional_purchase_ajax/getProductInfo?id=${id}`);

    for (const attachment of attachmentSet.value) {
      attachment.open = false;
    }

    const item = uniqueItem(res.data.data);
    item.open = true;

    attachmentSet.value.unshift(item);

    productSelector.value.close();
  }

  productSelector.value.open(url, { size: 'modal-xl' });
}

function checkAvailable(id: string | number) {
  // Check is in attachments
  for (const { product } of attachmentSet.value) {
    if (Number(product.id) === Number(id)) {
      throw new Error(__('shopgo.additional.purchase.message.already.selected'));
    }
  }

  // Check is in targets
  for (const target of document.querySelectorAll<HTMLElement>('#input-item-products-wrap .list-group-item')) {
    if (Number(target.dataset.value) === Number(id)) {
      throw new Error(__('shopgo.additional.purchase.message.already.in.targets'));
    }
  }
}

function removeProduct(i: number) {
  attachmentSet.value.splice(i, 1);
}

</script>

<template>
  <div class="l-ap-attachments" data-novalidate>

    <input name="attachments" type="hidden" value="__EMPTY_ARRAY__" />

    <div class="mb-3">
      <button v-if="attachmentSet.length > 0" type="button" class="btn btn-primary btn-sm"
        style="min-width: 100px"
        @click="openProductSelector"
      >
        <i class="fa fa-plus"></i>
        {{ $lang('shopgo.additional.purchase.button.add.product') }}
      </button>
    </div>

    <transition-group v-if="attachmentSet.length > 0" name="fade">
      <AttachmentProduct
        v-for="({ product, variants, open }, i) of attachmentSet" :key="product.id"
        :product="product"
        :variants="variants"
        :open="open"
        @remove="removeProduct(i)"
        class="mb-4"
        style="animation-duration: .3s"
      ></AttachmentProduct>
    </transition-group>

    <div v-else class="card bg-light">
      <div class="card-body text-center py-5">
        <button type="button" class="btn btn-primary"
          style="min-width: 100px"
          @click="openProductSelector"
        >
          <i class="fa fa-plus"></i>
          {{ $lang('shopgo.additional.purchase.button.add.product') }}
        </button>
      </div>
    </div>

    <uni-iframe-modal ref="productSelector"></uni-iframe-modal>
  </div>
</template>

<style scoped>

</style>

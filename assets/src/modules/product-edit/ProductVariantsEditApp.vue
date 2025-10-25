<script setup lang="ts">

import { uniqueItemList } from '@lyrasoft/ts-toolkit/vue';
import { __, simpleConfirm } from '@windwalker-io/unicorn-next';
import { Md5 } from 'ts-md5';
import { provide, computed, ref } from 'vue';
import VariantGeneration from '~shopgo/modules/product-edit/components/VariantGeneration.vue';
import VariantInfoEdit from '~shopgo/modules/product-edit/components/VariantInfoEdit.vue';
import VariantListItem from '~shopgo/modules/product-edit/components/VariantListItem.vue';
import { Product, ProductVariant } from '~shopgo/types';

const props = defineProps<{
  product: Product;
  variants: ProductVariant[];
}>();

const priceInput = document.querySelector<HTMLInputElement>('#input-item-variant-price')!;
const form = document.querySelector<HTMLFormElement>('#admin-form')!;

// Split state into separate refs
const items = ref<ProductVariant[]>(prepareItems(props.variants));
const generate = ref({
  edit: false
});
const lastCheckItemIndex = ref<number | null>(0);

const mainPrice = ref<string>(parseFloat(priceInput.value).toString());

provide('product', props.product || {});
provide('mainPrice', mainPrice);

priceInput.addEventListener('change', () => {
  mainPrice.value = parseFloat(priceInput.value).toString();
});

// Unsave
let formSubmitting = false;
const initialHash = Md5.hashStr(JSON.stringify(items.value));
const saveRequired = computed(() => Md5.hashStr(itemsJSON.value) !== initialHash);

window.addEventListener('beforeunload', (e) => {
  if (saveRequired.value && !formSubmitting) {
    e.preventDefault();
    e.stopPropagation();
    e.returnValue = 'Save Required';

    return 'Save Required';
  }
});

form.addEventListener('submit', () => {
  formSubmitting = true;
});

const checkedItems = computed(() => {
  return items.value.filter((item) => item.checked);
});

function prepareItems(items: ProductVariant[]) {
  return uniqueItemList(items).map((item) => {
    item.checked = false;
    item.unsave = false;

    return item;
  });
}

function checkAll($event?: Event, value?: boolean) {
  const target = $event?.target as HTMLInputElement | undefined;

  items.value.forEach((item) => {
    item.checked = value == null ? target?.checked : value;
  });
}

async function multiCheck($event: MouseEvent, i: number) {
  const target = $event.target as HTMLInputElement;

  if ($event.shiftKey) {
    let k = lastCheckItemIndex.value as number;

    if ((lastCheckItemIndex.value as number) < i) {
      for (; k < i; k++) {
        items.value[k].checked = target.checked;
      }
    } else {
      for (; k > i; k--) {
        items.value[k].checked = target.checked;
      }
    }
  } else {
    items.value[i].checked = target.checked;

    if (lastCheckItemIndex.value === null) {
      lastCheckItemIndex.value = i;
      return;
    }
  }

  lastCheckItemIndex.value = i;
}

function countChecked() {
  return checkedItems.value.length;
}

// Editing
const current = computed<ProductVariant | undefined>(() => {
  if (checkedItems.value.length === 1) {
    return checkedItems.value[0];
  }

  return undefined;
});
const variantEdit = ref();

async function editVariant(item: ProductVariant) {
  if (!await cancelEdit()) {
    return;
  }

  checkAll(undefined, false);

  generate.value.edit = false;
  item.checked = true;
}

async function generateCombinations() {
  if (!await cancelEdit()) {
    return;
  }

  generate.value.edit = true;
}

function generated(variants: ProductVariant[]) {
  items.value = items.value.concat(prepareItems(variants));

  generate.value.edit = false;
}

async function cancelEdit() {
  checkAll(undefined, false);

  return true;
}

async function confirmLeave() {
  if (variantEdit.value) {
    if (variantEdit.value.unsave) {
      const v = await simpleConfirm(__('shopgo.message.save.required'));

      if (!v) {
        return false;
      }
    }
  }

  return true;
}

function deleteVariants(item?: ProductVariant) {
  if (!item) {
    items.value = items.value.filter(it => !it.checked);
  } else {
    items.value = items.value.filter(it => it.hash !== item.hash);
  }
}

// Input
const itemsJSON = computed(() => JSON.stringify(items.value));
</script>

<template>
  <div class="row" data-novalidate>
    <!-- Variants List -->
    <div class="col-lg-6 l-product-variant__list">
      <div class="card c-variant-list">
        <!-- Header -->
        <div class="card-header c-variant-list__toolbar d-flex">
          <div class="ms-auto">
            <button type="button" class="btn btn-sm btn-outline-danger"
              v-if="countChecked() > 0"
              @click="deleteVariants()"
              :disabled="generate.edit">
              <span class="fa fa-trash"></span>
              {{ $lang('shopgo.product.variant.button.delete.variants') }}
            </button>

            <button type="button" class="btn btn-sm btn-primary"
              @click="generateCombinations()" :disabled="generate.edit">
              <span class="fa fa-plus"></span>
              {{ $lang('shopgo.product.variant.button.add.variants') }}
            </button>
          </div>
        </div>

        <div class="c-variant-list__items list-group list-group-flush">
          <!-- Variant List Header -->
          <div class="list-group-item c-variant-list__header d-flex"
            style="margin-bottom: 0;">
            <div class="me-2">
              <input type="checkbox"
                class="form-check-input"
                @change="checkAll($event)"
                :indeterminate.prop="countChecked() > 0 && countChecked() < items.length" />
            </div>
            <div class="me-2" style="width: 45px;">
              {{ $lang('shopgo.product.variant.label.cover') }}
            </div>
            <div class="me-2 flex-fill">
              {{ $lang('shopgo.product.variant.label.options') }}
            </div>
            <div class="me-2" style="width: 75px;">
              {{ $lang('shopgo.product.variant.label.stock.quantity') }}
            </div>
            <div class="" style="width: 66px;">
              {{ $lang('shopgo.product.variant.label.actions') }}
            </div>
          </div>

          <!-- Variants -->
          <div class="c-variant-list__scroll list-group list-group-flush"
            style="overflow-y: scroll; height: 75vh; min-height: 400px">
            <transition-group name="fade">
              <VariantListItem
                v-for="(item, i) of items"
                :key="item.uid"
                :data-id="item.id"
                :item="item"
                :i="i"
                :active="current?.hash === item.hash"
                @edit="editVariant"
                @remove="deleteVariants(item)"
                @oncheck="multiCheck"
                style="animation-duration: .3s"
              />
            </transition-group>
          </div>
        </div>
      </div>
    </div>

    <!-- Right -->
    <div class="col-lg-6 l-product-variant__manage">
      <VariantInfoEdit v-if="checkedItems.length"
        ref="variantEdit"
        :variants="checkedItems"
        @cancel="cancelEdit"
      ></VariantInfoEdit>

      <VariantGeneration v-if="generate.edit"
        :items="items"
        @generated="generated"
        @cancel="generate.edit = false;"
        class="">
      </VariantGeneration>
    </div>

    <textarea name="variants" class="d-none" :value="itemsJSON"></textarea>
  </div>
</template>

<style scoped>

</style>

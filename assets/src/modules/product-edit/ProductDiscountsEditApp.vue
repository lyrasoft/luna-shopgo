<script setup lang="ts">
import { uniqueItemList } from '@lyrasoft/ts-toolkit/vue';
import { data } from '@windwalker-io/unicorn-next';
import { Md5 } from 'ts-md5';
import { computed, onMounted, ref, watch } from 'vue';
import { vTooltip } from '~shopgo/directives';
import { Discount, Product } from '~shopgo/types';
import { VueDraggable } from 'vue-draggable-plus';

const props = defineProps<{
  product: Product;
  discounts: Discount[];
}>();

const dateFormat = 'Y-m-d H:i:S';

function prepareItems(items: Discount[]) {
  return uniqueItemList(items).map((item) => {
    item.checked = false;
    item.unsave = false;

    return item;
  });
}

// split state into individual refs
const items = ref<Discount[]>(prepareItems(props.discounts || []));
const current = ref<Discount>();
const currentIndex = ref<number>(-1);
const currentHash = ref<string>('');
const lastCheckItemIndex = ref<number | null>(null);
const flatpickrOptions = ref<string>(JSON.stringify({
  dateFormat,
  enableTime: true,
  enableSeconds: true,
  allowInput: true,
  time_24hr: true,
  monthSelect: false,
}));
const inputStep = ref<string>(data('input.step') || '0.0001');

const itemsJSON = computed(() => JSON.stringify(items.value));
// const currentItemsHash = computed(() => Md5.hashStr(itemsJSON.value));

let formSubmitting = false;
const initialHash = Md5.hashStr(JSON.stringify(items.value));
const saveRequired = computed(() => Md5.hashStr(itemsJSON.value) !== initialHash);
const checkedItems = computed(() => items.value.filter((it: any) => it.checked));
// const currentEditUnsave = computed(() => current.value.currentCopy !== JSON.stringify(current.value));

function hashItem(item: any) {
  const newItem = { ...item };
  delete newItem.checked;
  delete newItem.unsave;
  return Md5.hashStr(JSON.stringify(newItem));
}

function dateToSQLFormat(dateStr: string | null) {
  if (!dateStr) {
    return dateStr;
  }

  if (!flatpickr) {
    return dateStr;
  }

  return flatpickr.formatDate(flatpickr.parseDate(dateStr), dateFormat);
}

function checkAll(event: Event) {
  const target = event.target as HTMLInputElement;
  items.value.forEach((item: any) => {
    item.checked = target.checked;
  });
}

function multiCheck(event: MouseEvent | KeyboardEvent, item: any, i: number) {
  if (lastCheckItemIndex.value === null) {
    lastCheckItemIndex.value = i;
    return;
  }

  if ((event as MouseEvent).shiftKey) {
    let k = lastCheckItemIndex.value as number;
    const checked = (event.target as HTMLInputElement).checked;

    if (lastCheckItemIndex.value! < i) {
      for (; k < i; k++) {
        items.value[k].checked = checked;
      }
    } else {
      for (; k > i; k--) {
        items.value[k].checked = checked;
      }
    }
  }

  lastCheckItemIndex.value = i;
}

function countChecked() {
  return checkedItems.value.length;
}

function newItem() {
  const item: any = {
    id: null,
    productId: props.product?.id,
    type: 'product',
    subtype: 'discount',
    minProductQuantity: 0,
    price: '',
    start_date: null,
    end_date: null,
    method: 'offsets',
    state: 1,
  };

  prepareItems([item]);
  items.value.push(item);
  editItem(item, items.value.length - 1);
}

watch(() => current.value?.method, (method: string | undefined) => {
  if (!current.value || currentIndex.value === -1) {
    return;
  }

  if (['percentage', 'fixed'].indexOf(method as string) !== -1) {
    current.value.price = Math.abs(current.value.price);
  } else {
    current.value.price = -Math.abs(current.value.price);
  }
});

async function editItem(item: any, i: number) {
  item.publishUp = dateToSQLFormat(item.publishUp);
  item.publishDown = dateToSQLFormat(item.publishDown);

  currentHash.value = hashItem(item);
  current.value = item;
  currentIndex.value = i;
}

watch(
  () => current.value,
  () => {
    if (currentHash.value !== '' && currentHash.value !== hashItem(current.value)) {
      if (currentIndex.value >= 0 && currentIndex.value < items.value.length) {
        items.value[currentIndex.value].unsave = true;
      }
    }
  },
  { deep: true }
);

function cancelEdit() {
  currentHash.value = '';
  current.value = undefined;
  currentIndex.value = -1;
}

function deleteItems(item?: Discount) {
  if (!item) {
    items.value = items.value.filter((it: any) => {
      if (it.checked && it.uid === current.value?.uid) {
        cancelEdit();
      }
      return !it.checked;
    });
  } else {
    if (item.uid === current.value?.uid) {
      cancelEdit();
    }
    items.value = items.value.filter((it: any) => it.uid !== item.uid);
  }
}

function reorder() {
  items.value.forEach((item: any, i: number) => {
    item.ordering = i + 1;
  });
}

function timeLimit(item: any) {
  let text = '';
  if (item.publishUp) {
    text += new Date(item.publishUp).toLocaleString(undefined, { timeZone: 'UTC' });
  } else {
    text += '現在';
  }
  text += ' 到 ';
  if (item.publishDown) {
    text += new Date(item.publishDown).toLocaleString(undefined, { timeZone: 'UTC' });
  } else {
    text += '不限期';
  }
  return text;
}

function correctPriceInput() {
  if (!current.value) {
    return;
  }

  if (current.value.method === 'fixed') {
    current.value.price = Math.max(current.value.price, 0);
  } else if (current.value.method === 'offsets') {
    current.value.price = Math.min(current.value.price, 0);
  } else {
    current.value.price = Math.max(current.value.price, 0);
    current.value.price = Math.min(current.value.price, 100);
  }
}

function onPriceInput(e: Event) {
  if (!current.value) {
    return;
  }

  current.value.price = Number((e.target as HTMLInputElement).value);
}

const mainPrice = ref('');

onMounted(() => {
  const priceInput = document.querySelector<HTMLInputElement>('#input-item-variant-price')!;
  const formElement = document.querySelector<HTMLFormElement>('#admin-form')!;

  mainPrice.value = parseFloat(priceInput.value).toString();

  priceInput.addEventListener('change', () => {
    mainPrice.value = parseFloat(priceInput.value).toString();
  });

  formElement.addEventListener('submit', (e) => {
    formSubmitting = true;
  });

  window.addEventListener('beforeunload', (e) => {
    if (saveRequired.value && !formSubmitting) {
      e.preventDefault();
      e.stopPropagation();
      e.returnValue = 'Save Required';

      return 'Save Required';
    }
  });
});
</script>

<template>
  <div class="l-product-discount row" data-novalidate>
    <div class="col-lg-6 l-product-discount__list">
      <div class="card c-discount-list">
        <div class="card-header c-discount-list__toolbar d-flex">
          <div class="ms-auto">
            <button type="button" class="btn btn-sm btn-outline-danger"
              v-if="countChecked() > 0"
              @click="deleteItems()">
              <span class="fa fa-trash"></span>
              {{ $lang('shopgo.product.button.delete') }}
            </button>

            <button type="button" class="btn btn-sm btn-primary"
              @click="newItem()">
              <span class="fa fa-plus"></span>
              {{ $lang('shopgo.product.discount.button.new') }}
            </button>
          </div>
        </div>

        <div class="c-discount-list__items list-group list-group-flush">
          <div class="list-group-item c-discount-list__header d-flex gap-2" style="margin-bottom: 0;">
            <div class="">
              <span class="fa fa-arrows-alt-v fa-fw me-1"></span>
              <input type="checkbox" @change="checkAll($event)"
                class="form-check-input"
                :indeterminate.prop="countChecked() > 0 && countChecked() < items.length" />
            </div>
            <div class="flex-fill" style="">
              {{ $lang('shopgo.product.discount.field.type') }}
            </div>
            <div class="text-end" style="width: 100px;">
              {{ $lang('shopgo.discount.field.min.product.quantity') }}
            </div>
            <div class="text-end" style="width: 100px;">
              {{ $lang('shopgo.product.discount.field.price.offsets') }}
            </div>
            <div class="" style="width: 75px;">
              {{ $lang('shopgo.product.discount.field.time') }}
            </div>
            <div class="" style="width: 75px;">
              {{ $lang('shopgo.product.discount.actions') }}
            </div>
          </div>

          <!-- List -->
          <div class="c-discount-list__scroll list-group list-group-flush"
            style="overflow-y: scroll; height: 75vh; min-height: 400px">
            <VueDraggable v-model="items" @sort="reorder"
              :animation="300"
              handle=".handle"
              item-key="uid"
            >
              <!-- Discount Item-->
              <template v-for="(item, i) in items" :key="item.uid">
                <div class="list-group-item c-discount-item"
                  :class="{ 'text-bg-dark': current?.uid === item.uid }"
                  :data-id="item.id"
                >
                  <div class="list-group-item__wrapper d-flex align-items-center gap-2">
                    <!-- Checkbox -->
                    <div class="c-discount-item__control d-flex flex-nowrap">
                      <span class="fa fa-fw fa-ellipsis-v handle" style="cursor: move;"></span>
                      <input type="checkbox" v-model="item.checked"
                        class="form-check-input"
                        @click="multiCheck($event, item, i)" />
                    </div>

                    <!-- Type -->
                    <div class="c-discount-item__type flex-fill text-nowrap">
                      {{ $lang('shopgo.discount.subtype.' + item.subtype) }}
                      <div v-if="item.unsave">
                        <span class="badge bg-warning">
                            {{ $lang('shopgo.product.text.save.required') }}
                        </span>
                      </div>
                    </div>

                    <!-- Start Qty -->
                    <div class="c-discount-item__quantity text-end"
                      style="width: 100px;">
                      {{ item.subtype === 'discount' ? item.minProductQuantity : '-' }}
                    </div>

                    <!-- Pricing -->
                    <div class="c-discount-item__price text-end flex-fill"
                      style="width: 100px">
                      {{ $priceOffset(item.price, item.method) }}
                    </div>

                    <!-- Time -->
                    <div class="c-discount-item__time-limit text-center"
                      style="width: 75px;">
                      <span v-if="item.publishUp || item.publishDown"
                        class="fa fa-clock has-tooltip"
                        v-tooltip
                        :title="timeLimit(item)"
                      ></span>
                      <span v-else>-</span>
                    </div>

                    <!-- Actions -->
                    <div class="c-discount-item__actions text-nowrap text-end"
                      style="width: 75px">
                      <button type="button" class="btn btn-sm btn-light border-secondary"
                        @click="editItem(item, i)">
                        <span class="fa fa-pencil-alt"></span>
                      </button>
                      <button type="button" class="btn btn-sm btn-light border-secondary"
                        @click="deleteItems(item)">
                        <span class="fa fa-trash text-danger"></span>
                      </button>
                    </div>
                  </div>
                </div>
              </template>
            </VueDraggable>
          </div>
        </div>
      </div>
    </div>

    <!-- Edit -->
    <div class="col-lg-6 l-product-discount__manage">
      <div v-if="current" class="c-discount-edit card">
        <div class="card-header d-flex">
          <div class="c-discount-edit__title">
            {{ $lang('shopgo.product.discount.edit.title') }}
          </div>
          <div class="c-discount-edit__actions ms-auto">

          </div>
        </div>
        <div class="card-body">
          <div class="d-flex gap-2">
            <!-- Mode -->
            <div class="form-group mb-4">
              <label for="input-discount-subtype" class="form-label">
                {{ $lang('shopgo.product.discount.field.mode') }}
              </label>
              <select id="input-discount-subtype" class="form-select"
                style="min-width: 100px;"
                v-model="current.subtype">
                <option value="discount">
                  {{ $lang('shopgo.discount.subtype.discount') }}
                </option>
                <option value="special">
                  {{ $lang('shopgo.discount.subtype.special') }}
                </option>
              </select>
            </div>

            <!-- Start Qty -->
            <transition name="fade">
              <div class="form-group mb-4" v-if="current.subtype === 'discount'"
                style="animation-duration: .3s">
                <label for="input-discount-quantity" class="form-label">
                  {{ $lang('shopgo.discount.field.min.product.quantity') }}
                </label>
                <input id="input-discount-quantity" type="number" class="form-control"
                  v-model="current.minProductQuantity" min="0" />
              </div>
            </transition>
          </div>

          <div class="d-flex gap-2">
            <!-- Publish Up -->
            <div class="form-group mb-4">
              <label for="input-discount-start_date" class="form-label">
                {{ $lang('shopgo.discount.field.publish.up') }}
              </label>
              <uni-flatpickr :options="flatpickrOptions">
                <div class="input-group" data-calendar>
                  <input id="input-discount-start_date" type="text" class="form-control"
                    v-model="current.publishUp"
                    data-input
                  />

                  <button type="button"
                    class="btn btn-secondary"
                    data-toggle
                  >
                    <span class="fa fa-calendar"></span>
                  </button>
                  <button type="button"
                    class="btn btn-secondary"
                    data-clear
                    @click="current.publishUp = ''"
                  >
                    <span class="fa fa-times"></span>
                  </button>
                </div>
              </uni-flatpickr>
            </div>

            <!-- Publish Down -->
            <div class="form-group mb-4">
              <label for="input-discount-end_date" class="form-label">
                {{ $lang('shopgo.discount.field.publish.down') }}
              </label>
              <uni-flatpickr :options="flatpickrOptions">
                <div class="input-group" data-calendar>
                  <input id="input-discount-end_date" type="text" class="form-control"
                    v-model="current.publishDown"
                    data-input
                  />

                  <button type="button"
                    class="btn btn-secondary"
                    data-toggle
                  >
                    <span class="fa fa-calendar"></span>
                  </button>
                  <button type="button"
                    class="btn btn-secondary"
                    data-clear
                    @click="current.publishDown = ''"
                  >
                    <span class="fa fa-times"></span>
                  </button>
                </div>
              </uni-flatpickr>
            </div>
          </div>

          <div class="d-flex gap-2">
            <!-- Pricing -->
            <div class="form-group mb-4">
              <label for="input-discount-price" class="form-label">
                {{ $lang('shopgo.product.discount.field.price.offsets') }}
              </label>
              <div class="input-group">
                <input id="input-discount-price" type="number" class="form-control"
                  :value="current.price"
                  @input="onPriceInput"
                  @change="correctPriceInput"
                  :step="current.method === 'percentage' ? 0.1 : inputStep"
                />
                <span v-if="current.method === 'percentage'" class="input-group-text">
                    %
                </span>
              </div>
            </div>

            <!-- Pricing Method -->
            <div class="form-group mb-4">
              <label for="input-discount-method" class="form-label">
                {{ $lang('shopgo.discount.field.method') }}
              </label>
              <select id="input-discount-method" class="form-select"
                v-model="current.method">
                <option value="percentage">
                  {{ $lang('shopgo.discount.method.percentage') }}
                </option>
                <option value="offsets">
                  {{ $lang('shopgo.discount.method.offsets') }}
                </option>
                <option value="fixed">
                  {{ $lang('shopgo.discount.method.fixed') }}
                </option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>

    <textarea name="discounts" class="d-none" :value="itemsJSON"></textarea>
  </div>
</template>

<style scoped>

</style>

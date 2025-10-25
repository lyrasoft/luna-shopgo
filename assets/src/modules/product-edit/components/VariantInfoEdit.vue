<script setup lang="ts">
import { data, route, useStack } from '@windwalker-io/unicorn-next';
import { SortableOptions } from 'sortablejs';
import { Md5 } from 'ts-md5';
import { ref, computed, watch } from 'vue';
import { VueDraggable } from 'vue-draggable-plus';
import { ProductVariant } from '~shopgo/types';
import { mergeRecursive } from '~shopgo/utilities';
import { MultiUploader, ItemCard, ItemCardPlaceholder} from 'vue-multi-uploader';

const props = defineProps<{
  variants: ProductVariant[];
}>();

const emit = defineEmits<{
  cancel: [];
}>()

const current = ref<ProductVariant | null>(null);
const items = ref<ProductVariant[]>([]);
const currentHash = ref('');
const flatpickrOptions = ref<string>(
  JSON.stringify(
    {
      dateFormat: 'Y-m-d H:i:S',
      enableTime: true,
      enableSeconds: true,
      allowInput: true,
      time_24hr: true,
      // wrap: true,
      monthSelect: false,
    }
  )
);
const stack = useStack('uploading');
const inputStep = ref<string>(data('input.step') || '0.0001');

watch(() => props.variants, (v) => {
  let item: any = {
    sku: '',
    price: '',
    stockQuantity: '',
    publishUp: '',
    publishDown: '',
    images: [],
    dimension: {
      width: '',
      height: '',
      length: '',
      weight: '',
      unitWeight: '',
    }
  };
  items.value = props.variants;

  if (items.value.length === 1) {
    item = items.value[0];
  }

  currentHash.value = hashItem(item);

  current.value = item;
}, { immediate: true });

function hashItem(item: ProductVariant) {
  const newItem = { ...item };

  delete newItem.checked;
  delete newItem.unsave;

  return Md5.hashStr(JSON.stringify(newItem));
}

const isMultiple = computed(() => items.value.length > 1);
// const unsave = computed(() => state.originCopy !== JSON.stringify(state.current));

watch(() => current.value, () => {
  if (currentHash.value !== '' && currentHash.value !== hashItem(current.value!)) {
    updateUnsaves();
  }
}, { deep: true });

watch(() => current.value?.price, (v) => {
  if (!current.value) {
    return;
  }

  if (v != null && v < 0) {
    current.value.price = 0;
  }
});

function updateUnsaves() {
  if (!current.value) {
    return;
  }

  if (!isMultiple.value) {
    current.value.cover = current.value.images[0]?.url || '';
    items.value[0].unsave = true;
  } else {
    for (const item of items.value) {
      mergeRecursive(
        item,
        current.value,
      );

      item.unsave = true;
    }
  }
}

function cancelEdit() {
  emit('cancel');
}

function getImageUploaderUrl() {
  return route('file_upload', { profile: 'image' });
}

const draggableOptions: SortableOptions = {
  handle: '.item',
  animation: 150,
};
</script>

<template>
  <div class="c-variant-edit card">
    <div class="card-header d-flex align-items-center">
      <div class="c-variant-edit__title d-flex gap-2">
        <div>
          {{ $lang('shopgo.product.variant.edit.title') }}
        </div>
      </div>
      <div class="c-variant-edit__actions ms-auto">
        <button type="button" class="btn btn-outline-secondary btn-sm"
          @click="cancelEdit">
          <span class="fa fa-times"></span>
          {{ $lang('shopgo.product.button.cancel') }}
        </button>
      </div>
    </div>
    <div v-if="current" class="card-body">
      <div class="c-variant-edit__title mb-4">
        <span
          class="lead">{{ items.length <= 1 ? current.title : $lang('shopgo.product.variant.edit.multiple') }}</span>
      </div>

      <!--        <div class="d-flex mb-2 align-items-center" v-if="items.length <= 1">-->
      <!--            <label for="input-variant-default" class="mr-2">設為預設</label>-->
      <!--            <phoenix-switch name="default" v-model="current.default" size="sm"-->
        <!--                true-value="1"-->
        <!--                false-value="0"-->
        <!--                shape="circle"></phoenix-switch>-->
      <!--        </div>-->

      <div class="d-flex gap-2">
        <!--            <div class="form-group mb-4" v-if="items.length <= 1">-->
        <!--                <label for="input-variant-model">型號</label>-->
        <!--                <input id="input-variant-model" type="text" class="form-control"-->
          <!--                    v-model="current.model" />-->
        <!--            </div>-->
        <div class="form-group mb-4" v-if="items.length <= 1">
          <label for="input-variant-sku" class="form-label">
            {{ $lang('shopgo.product.field.sku') }}
          </label>
          <textarea id="input-variant-sku" type="text" class="form-control"
            v-model="current.sku" rows="1"></textarea>
        </div>

        <div class="form-group mb-4">
          <label for="input-variant-price" class="form-label">
            {{ $lang('shopgo.product.field.price') }}
          </label>
          <input id="input-variant-price" type="number" class="form-control"
            v-model="current.price"
            min="0"
            :step="inputStep"
          />
        </div>
      </div>

      <div class="d-flex gap-2">
        <div class="form-group mb-4">
          <label for="input-variant-length" class="form-label">
            {{ $lang('shopgo.product.field.length') }}
          </label>
          <input id="input-variant-length" type="number" class="form-control"
            v-model="current.dimension.length"
            min="0"
          />
        </div>
        <div class="form-group mb-4">
          <label for="input-variant-width" class="form-label">
            {{ $lang('shopgo.product.field.width') }}
          </label>
          <input id="input-variant-width" type="number" class="form-control"
            v-model="current.dimension.width"
            min="0"
          />
        </div>
        <div class="form-group mb-4">
          <label for="input-variant-height" class="form-label">
            {{ $lang('shopgo.product.field.height') }}
          </label>
          <input id="input-variant-height" type="number" class="form-control"
            v-model="current.dimension.height"
            min="0"
          />
        </div>
        <div class="form-group mb-4">
          <label for="input-variant-weight" class="form-label">
            {{ $lang('shopgo.product.field.weight') }}
          </label>
          <input id="input-variant-weight" type="number" class="form-control"
            v-model="current.dimension.weight"
            min="0"
          />
        </div>
      </div>

      <div class="d-flex gap-2">
        <div class="form-group mb-4">
          <label for="input-variant-inventory" class="form-label">
            {{ $lang('shopgo.product.field.stock.quantity') }}
          </label>
          <input id="input-variant-inventory" type="number" class="form-control"
            v-model="current.stockQuantity" min="0" />
        </div>
        <div class="form-group mb-4">
          <label for="input-variant-subtract" class="form-label">
            {{ $lang('shopgo.product.field.subtract') }}
          </label>
          <div class="form-check form-switch">
            <input type="checkbox" id="input-variant-subtract"
              class="form-check-input"
              v-model="current.subtract"
              :true-value="true"
              :false-value="false"
              role="switch"
            />
          </div>
        </div>
      </div>

      <div class="variant-images mt-4" v-if="items.length <= 1">
        <MultiUploader
          :upload-url="getImageUploaderUrl()"
          v-model="current.images"
          :options="{
            maxFiles: 6,
            accept: 'image/*',
          }"
          @uploading="stack.push(true)"
          @uploaded="stack.pop()"
        >
          <template #items="{ instance, instance: { canUpload, openFileSelector, deleteItem } }">
            <VueDraggable v-model="instance.items" v-bind="draggableOptions"
              class="d-flex flex-wrap w-100 gap-3"
            >
              <ItemCard v-for="(item, index) of instance.items"
                :key="item.key"
                class="item"
                :item
                :i="index"
                @delete="deleteItem"
              />

              <ItemCardPlaceholder
                v-if="canUpload"
                class=""
                text="Upload Images"
                @click="openFileSelector"
              />
            </VueDraggable>
          </template>
        </MultiUploader>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>

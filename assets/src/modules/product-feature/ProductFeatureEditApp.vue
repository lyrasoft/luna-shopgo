<script setup lang="ts">
import { uniqueItem, uniqueItemList } from '@lyrasoft/ts-toolkit/vue';
import { useCssImport } from '@windwalker-io/unicorn-next';
import { ref } from 'vue';
import { VueDraggable } from 'vue-draggable-plus';
import { vColorpicker } from '~shopgo/directives';
import { ListOption } from '~shopgo/types';

useCssImport('@vue-animate');

type ProductFeatureOptionItem = {
  data: ListOption;
  uid: string;
  selected: boolean;
};

const $typeSelect = document.querySelector<HTMLSelectElement>('#input-item-type')!;

const props = defineProps<{
  options: ListOption[];
}>();

const type = ref($typeSelect.value);
const items = ref<ProductFeatureOptionItem[]>(
  uniqueItemList(props.options || []).map((item) => ({
    data: item,
    uid: item.uid,
    selected: false
  }))
);

const current = ref<ProductFeatureOptionItem | null>(null);
const selected = ref<string[]>([]);
const colorPickerOptions = ref<Record<string, unknown>>({});

$typeSelect.addEventListener('change', () => {
  type.value = $typeSelect.value;
});

function selectItem(item: ProductFeatureOptionItem) {
  current.value = item;
}

function addNewItem(item?: ProductFeatureOptionItem) {
  const i = item ? items.value.indexOf(item) + 1 : items.value.length;

  const data = uniqueItem({
    value: '',
    text: '',
    color: ''
  });
  const newItem = {
    data,
    uid: data.uid,
    selected: false
  };

  items.value.splice(i, 0, newItem);

  selectItem(newItem);
}

function removeItem(item: ProductFeatureOptionItem) {
  const i = items.value.indexOf(item);

  items.value.splice(i, 1);
}

function removeItems() {
  items.value = items.value.filter((item) => !selected.value.includes(item.uid));

  if (selected.value.includes(current.value?.uid || '')) {
    current.value = null;
  }

  selected.value = [];
}

function toJson(data: ProductFeatureOptionItem) {
  return JSON.stringify(data);
}
</script>

<template>
  <div class="row">
    <div class="col-lg-6">
      <div class="card c-feature-option-list">
        <div class="card-header d-flex align-items-center">
          <h3 class="m-0">
            {{ $lang('shopgo.product.feature.options.title') }}
          </h3>
          <div class="c-list-top-toolbar ms-auto">
            <button type="button" class="btn btn-sm btn-primary"
              @click="addNewItem()">
              <span class="fa fa-plus"></span>
              {{ $lang('shopgo.product.feature.button.new') }}
            </button>
            <button type="button" class="btn btn-sm btn-outline-danger"
              @click="removeItems()" :disabled="selected.length === 0">
              <span class="fa fa-trash"></span>
              {{ $lang('shopgo.product.feature.button.delete') }}
            </button>
          </div>
        </div>

        <div class="c-option-list list-group list-group-flush">
          <VueDraggable v-model="items" handle=".handle" :animation="150">
            <TransitionGroup name="fade">
              <template v-for="item of items" :key="item.uid">
                <div class="list-group-item c-option-item"
                  :class="[{active: current === item}]"
                  @click="selectItem(item)"
                  style="cursor: pointer; animation-duration: .3s;"
                >
                  <div class="d-flex align-items-center gap-2">
                    <div class="c-option-item__control">
                                                        <span class="fa fa-fw fa-ellipsis-v handle"
                                                          style="cursor: move"
                                                        ></span>
                      <input type="checkbox" name="selected[]" v-model="selected"
                        class="form-check-input"
                        :value="item.uid"
                        @click.stop="" />
                    </div>
                    <div v-if="type === 'color'" class="c-option-item__color">
                      <div class="c-option-item__color-box rounded"
                        style="width: 25px; height: 25px;"
                        :style="{'background-color': item.data.color || '#eee'}"></div>
                    </div>
                    <div class="c-option-control__title flex-grow-1">
                      <div class="h5 m-0">
                        {{ item.data.text || $lang('shopgo.product.feature.text.unnamed') }}
                      </div>
                    </div>
                    <div
                      class="c-option-control__actions d-flex align-items-center gap-1">
                      <!--<div class="mr-2" @click.stop="">-->
                      <!--<label :for="'default-radio-' + item.uid">預設</label>-->
                      <!--<input type="radio" name="item[default]" :value="item.uid"-->
                      <!--:id="'default-radio-' + item.uid"-->
                      <!--@click="setDefault(i)" />-->
                      <!--</div>-->
                      <button type="button"
                        class="btn btn-sm btn-light border-secondary"
                        @click.stop="addNewItem(item)">
                        <span class="fa fa-plus"></span>
                      </button>
                      <button type="button" class="btn btn-sm btn-outline-danger"
                        @click.stop="removeItem(item)">
                        <span class="fa fa-trash"></span>
                      </button>
                    </div>
                  </div>
                  <div class="d-none">
                    <input type="hidden" :name="`options[${item.uid}][uid]`"
                      :value="item.data.uid" />
                    <input type="hidden" :name="`options[${item.uid}][text]`"
                      :value="item.data.text" />
                    <input type="hidden" :name="`options[${item.uid}][value]`"
                      :value="item.data.value" />
                    <input type="hidden" :name="`options[${item.uid}][color]`"
                      :value="item.data.color" />
                  </div>
                </div>
              </template>
            </TransitionGroup>
          </VueDraggable>
        </div>
      </div>
    </div>
    <div class="col-lg-6 l-feature-option-item">
      <div class="card c-option-edit" :key="current?.uid">
        <div class="card-header">
          {{ $lang('shopgo.product.feature.option.data.title') }}
        </div>
        <div class="card-body">
          <div v-if="current" class="c-option-edit__form">
            <div class="form-group mb-4">
              <label for="input-option-text" class="form-label">
                {{ $lang('shopgo.product.feature.option.text') }}
              </label>
              <input id="input-option-text" type="text" class="form-control"
                v-model="current.data.text" />
            </div>

            <div class="form-group mb-4">
              <label for="input-option-value" class="form-label">
                {{ $lang('shopgo.product.feature.option.value') }}
              </label>
              <input id="input-option-value" type="text" class="form-control"
                v-model="current.data.value" />
            </div>

            <div class="form-group mb-4" v-if="type === 'color'">
              <label for="input-option-value" class="form-label">
                {{ $lang('shopgo.product.feature.option.color') }}
              </label>
              <div>
                <input id="input-option-color" type="text"
                  v-colorpicker="colorPickerOptions"
                  class="form-control"
                  v-model.lazy="current.data.color"
                />
              </div>
            </div>
          </div>
          <div v-else>
            <div class="card bg-light">
              <div class="card-body text-center">
                {{ $lang('shopgo.product.feature.option.no.select') }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>

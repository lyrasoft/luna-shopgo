<script setup lang="ts">
import { uniqueItem, uniqueItemList } from '@lyrasoft/ts-toolkit/vue';
import { data, useCssImport } from '@windwalker-io/unicorn-next';
import { ref } from 'vue';
import { VueDraggable } from 'vue-draggable-plus';
import { ListOption } from '~shopgo/types';

useCssImport('@vue-animate');

type AttributeOptionItem = {
  data: ListOption;
  uid: string;
  selected: boolean;
};

const props = defineProps<{
  options: ListOption[];
}>();

const $typeSelect = document.querySelector<HTMLSelectElement>('#input-item-type')!;

const items = ref<AttributeOptionItem[]>(
  uniqueItemList(props.options || []).map((item) => {
    return {
      data: item,
      uid: item.uid,
      selected: false
    };
  })
);
const type = ref($typeSelect.value);
const current = ref<AttributeOptionItem | null>(null);
const selected = ref<string[]>([]);
const defaultUid = ref<string | undefined>(items.value.find((item) => item.data.is_default)?.uid);

$typeSelect.addEventListener('change', () => {
  type.value = $typeSelect.value;
});

function selectItem(item: AttributeOptionItem) {
  current.value = item;
}

function addNewItem(item: AttributeOptionItem | null = null) {
  const i = item ? items.value.indexOf(item) + 1 : items.value.length;

  const data = uniqueItem({
    value: '',
    text: '',
    is_default: false
  });
  const newItem = {
    data,
    uid: data.uid,
    selected: false
  };
  items.value.splice(i, 0, newItem);

  selectItem(newItem);
}

function removeItem(item: AttributeOptionItem) {
  const i = items.value.indexOf(item);

  if (i !== -1) {
    items.value.splice(i, 1);
  }

  if (current.value === item) {
    current.value = null;
  }
}

function removeItems() {
  items.value = items.value.filter((it) => !selected.value.includes(it.uid));

  if (selected.value.includes(current.value?.uid || '')) {
    current.value = null;
  }

  selected.value = [];
}

function toJson(data: any) {
  return JSON.stringify(data);
}
</script>

<template>
  <div v-if="type !== 'bool'" class="row">
    <div class="col-lg-6">
      <div class="card c-feature-option-list">
        <div class="card-header d-flex align-items-center">
          <h3 class="m-0">
            {{ $lang('shopgo.product.attribute.options.title') }}
          </h3>
          <div class="c-list-top-toolbar ms-auto">
            <button type="button" class="btn btn-sm btn-primary"
              @click="addNewItem()">
              <span class="fa fa-plus"></span>
              {{ $lang('shopgo.product.attribute.button.new') }}
            </button>
            <button type="button" class="btn btn-sm btn-outline-danger"
              @click="removeItems()" :disabled="selected.length === 0">
              <span class="fa fa-trash"></span>
              {{ $lang('shopgo.product.attribute.button.delete') }}
            </button>
          </div>
        </div>

        <div class="c-option-list list-group list-group-flush">
          <VueDraggable v-model="items" handle=".handle" item-key="uid"
            :animation="150"
          >
            <TransitionGroup name="fade">
              <template v-for="item of items" :key="item.uid">
                <div class="list-group-item c-option-item"
                  :style="[ current === item ? 'background: rgba(var(--bs-primary-rgb), .3)' : '', 'animation-duration: .3s;' ]"
                  @click="selectItem(item)"
                  style="cursor: pointer;"
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
                    <div class="c-option-control__title flex-grow-1">
                    <span class="h5 m-0">
                        {{ item.data.text || $lang('shopgo.product.attribute.text.unnameed') }}
                    </span>
                      <span v-if="type === 'select'" style="opacity: .5">
                        ({{ item.data.value }})
                    </span>
                    </div>
                    <div
                      class="c-option-control__actions d-flex align-items-center gap-1">
                      <div class="form-check mb-0 me-2" @click.stop="">
                        <input type="radio"
                          :value="item.uid"
                          class="form-check-input"
                          :id="'default-radio-' + item.uid"
                          v-model="defaultUid"
                        />
                        <label :for="'default-radio-' + item.uid"
                          class="form-check-label">
                          {{ $lang('shopgo.product.attribute.text.default') }}
                        </label>
                      </div>
                      <button type="button"
                        class="btn btn-sm btn-outline-dark"
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
                    <input type="hidden" :name="`options[${item.uid}][text]`"
                      :value="item.data.text" />
                    <input type="hidden" :name="`options[${item.uid}][value]`"
                      :value="item.data.value" />
                    <input type="hidden" :name="`options[${item.uid}][color]`"
                      :value="item.data.color" />
                    <input type="hidden" :name="`options[${item.uid}][is_default]`"
                      :checked="item.uid === defaultUid"
                      :value="item.uid === defaultUid ? 1 : ''" />
                  </div>
                </div>
              </template>
            </TransitionGroup>
          </VueDraggable>
        </div>
      </div>
    </div>
    <div class="col-lg-6 l-feature-option-item">
      <div class="card c-option-edit">
        <div class="card-header">
          {{ $lang('shopgo.product.attribute.option.data.title') }}
        </div>
        <div class="card-body">
          <div v-if="current" class="c-option-edit__form">
            <div class="form-group mb-4">
              <label for="input-option-text" class="form-label">
                {{ $lang('shopgo.product.attribute.option.text') }}
              </label>
              <input id="input-option-text" type="text" class="form-control"
                v-model="current.data.text" />
            </div>

            <div v-if="type === 'select'" class="form-group mb-4">
              <label for="input-option-value" class="form-label">
                {{ $lang('shopgo.product.attribute.option.value') }}
              </label>
              <input id="input-option-value" type="text" class="form-control"
                v-model="current.data.value" />
            </div>
          </div>
          <div v-else>
            <div class="card bg-light">
              <div class="card-body text-center">
                {{ $lang('shopgo.product.attribute.option.no.select') }}
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

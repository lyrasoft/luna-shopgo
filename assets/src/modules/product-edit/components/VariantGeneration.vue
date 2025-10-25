<script setup lang="ts">
import { uniqueItemList } from '@lyrasoft/ts-toolkit/vue';
import { __, ApiReturn, data, simpleAlert, useHttpClient } from '@windwalker-io/unicorn-next';
import { inject, Ref, ref, computed, onMounted } from 'vue';
import { ListOption, Product, ProductFeature, ProductVariant } from '~shopgo/types';

const props = defineProps<{
  items: ProductVariant[];
}>();

const emit = defineEmits<{
  generated: [variants: ProductVariant[]];
  cancel: [];
}>()

const features = ref<ProductFeature[]>([]);
const loadingGenerating = ref(false);
const loadingGetFeatureOptions = ref(false);

const product = inject<Product>('product')!;
const mainPrice = inject<Ref<string>>('mainPrice')!;
const currentHashes = computed(() => props.items.map(item => item.hash));
const currentOptionUids = computed(() => {
  const options = new Set();

  for (const item of props.items) {
    for (const option of item.options) {
      options.add(option.uid);
    }
  }

  return Array.from(options);
});
const variantsLimit = data<number>('variants.limit') ?? 100;

onMounted(() => {
  getFeatureOptions();
});

async function getFeatureOptions() {
  loadingGetFeatureOptions.value = true;

  const { get } = await useHttpClient();

  try {
    const res = await get<ApiReturn<ProductFeature[]>>('@product_ajax/getFeatureOptions');

    features.value = uniqueItemList(res.data.data).map((feature) => {
      feature.checks = 0;

      return feature;
    });

    for (const feature of features.value) {
      let i = 0;
      for (const option of feature.options) {
        option.checked = currentOptionUids.value.includes(option.uid);

        if (option.checked) {
          i++;
        }
      }

      feature.checks = i;
    }
  } finally {
    loadingGetFeatureOptions.value = false;
  }
}

const combinationCount = computed(() => {
  return features.value.reduce((carry, feature) => {
    return feature.checks > 0 ? carry * feature.checks : carry;
  }, 1);
});

async function saveGenerate() {
  // Prevent too many selected
  if (combinationCount.value >= variantsLimit) {
    simpleAlert(
      __('shopgo.product.message.too.many.features.selected', combinationCount.value, variantsLimit),
      '',
      'warning'
    );
    return;
  }

  loadingGenerating.value = true;

  const { post } = await useHttpClient();

  try {
    const res = await post(
      '@product_ajax/generateVariants',
      {
        product_id: product?.id,
        options: getCheckedOptionGroup(),
        currentHashes: currentHashes.value
      }
    );

    const variants = res.data.data;

    for (const variant of variants) {
      variant.price = Number(mainPrice.value);
    }

    emit('generated', variants);
  } finally {
    loadingGenerating.value = false;
  }
}

function getCheckedOptionGroup() {
  const data: Record<string, ListOption[]> = {};

  for (const feature of features.value) {
    const options = feature.options
      .filter(option => option.checked);

    if (options.length > 0) {
      data[feature.id.toString()] = options;
    }
  }

  return data;
}

// function sortOptionGroups(featureOptGroups, parentGroup = []) {
//     featureOptGroups = [...featureOptGroups];
//     const currentOptions = featureOptGroups.pop();
//
//     let returnValue = [];
//
//     for (const option of currentOptions) {
//         const group = [...parentGroup];
//
//         group.push(option);
//
//         if (featureOptGroups.length > 0) {
//             returnValue = returnValue.concat(sortOptionGroups(featureOptGroups, group));
//         } else {
//             returnValue = returnValue.concat([group]);
//         }
//     }
//
//     return returnValue;
// }

function featureCheckboxChanged(feature: ProductFeature, $event: Event) {
  const target = $event.target as HTMLInputElement;

  feature.options.forEach(option => option.checked = target.checked);
  feature.checks = target.checked ? feature.options.length : 0;
}

function optionCheckboxChanged(feature: any, _option: any) {
  feature.checks = 0;

  feature.options.forEach((option: any) => {
    if (option.checked) {
      feature.checks++;
    }
  });
}

function cancel() {
  emit('cancel');
}
</script>

<template>
  <div class="c-variant-generate card sticky-top">
    <div class="card-header d-flex">
      <div class="c-variant-generate__title">
        {{ $lang('shopgo.product.variant.generation.title') }} ({{ combinationCount || 0 }})
      </div>
      <div class="c-variant-generate__actions ms-auto">
        <button type="button" class="btn btn-primary btn-sm"
          @click="saveGenerate" :disabled="loadingGenerating">
          <span class="fa fa-save"></span>
          {{ loadingGenerating ? $lang('shopgo.product.text.saving') : $lang('shopgo.product.variant.generation.button.submit') }}
        </button>
        <button type="button" class="btn btn-outline-secondary btn-sm"
          @click="cancel" :disabled="loadingGenerating">
          <span class="fa fa-times"></span>
          {{ $lang('shopgo.product.button.cancel') }}
        </button>
      </div>
    </div>

    <div v-if="!loadingGetFeatureOptions" class="c-feature-list list-group list-group-flush">
      <div v-for="feature of features" class="c-feature-item list-group-item">
        <!-- Feature Title-->
        <h4 class="mb-3 h5">
                <span>
                    <input type="checkbox" :checked="feature.options.length === feature.checks"
                      :id="'input-feature-' + feature.id"
                      class="form-check-input"
                      :indeterminate.prop="feature.checks !== 0 && feature.options.length > feature.checks"
                      @change="featureCheckboxChanged(feature, $event)" />
                </span>
          <label :for="'input-feature-' + feature.id">
            {{ feature.title }}
          </label>
        </h4>

        <!-- Feature Options -->
        <div class="c-option-list row">

          <!-- Feature Option Item -->
          <div v-for="option of feature.options" class="c-option-item col-md-4 col-6">
            <div class="c-option-item__input-wrapper form-check">

              <!-- Feature Input -->
              <input :id="'input-option-' + option.uid" type="checkbox"
                :value="option.uid"
                :name="`options[${feature.id}][${option.uid}]`"
                class="form-check-input"
                v-model="option.checked"
                @change="optionCheckboxChanged(feature, option)" />

              <!-- Feature Label -->
              <label :for="'input-option-' + option.uid" class="form-check-label d-flex align-items-center">
                <span v-if="feature.type === 'color'"
                  class="rounded me-2"
                  style="width: 20px; height: 20px;"
                  :style="{'background-color': option.color}"
                ></span>
                <span>
                  {{ option.text }}
                </span>
              </label>
            </div>
          </div>
        </div>
      </div>

      <div class="list-group-item">
        <button type="button" class="btn btn-primary btn-sm w-100"
          @click="saveGenerate" :disabled="loadingGenerating">
          <span class="fa fa-save"></span>
          {{ loadingGenerating ? $lang('shopgo.product.text.saving') : $lang('shopgo.product.variant.generation.button.submit') }}
        </button>
      </div>
    </div>
    <div v-else class="text-center card-body">
      {{ $lang('shopgo.product.text.loading') }}
    </div>
  </div>
</template>

<style scoped>

</style>

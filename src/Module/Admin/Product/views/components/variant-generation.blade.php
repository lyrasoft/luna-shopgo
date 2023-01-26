<?php

declare(strict_types=1);

namespace App\View;

use Windwalker\Core\Application\AppContext;

/**
 * @var $app AppContext
 */


?>

<script id="c-variant-generation" type="text/x-template">
<div class="c-variant-generate card sticky-top">
    <div class="card-header d-flex">
        <div class="c-variant-generate__title">
            產生商品組合 (@{{ combinationCount || 0 }})
        </div>
        <div class="c-variant-generate__actions ms-auto">
            <button type="button" class="btn btn-primary btn-sm"
                @click="saveGenerate(current)" :disabled="loading.generating">
                <span class="fa fa-save"></span>
                @{{ loading.generating ? '儲存中' : '建立組合' }}
            </button>
            <button type="button" class="btn btn-outline-secondary btn-sm"
                @click="cancel" :disabled="loading.generating">
                <span class="fa fa-times"></span>
                取消
            </button>
        </div>
    </div>

    <div v-if="!loading.getFeatureOptions" class="c-feature-list list-group list-group-flush">
        <div v-for="feature of features" class="c-feature-item list-group-item">
            {{-- Feature Title--}}
            <h4 class="mb-3 h5">
                <span>
                    <input type="checkbox" :checked="feature.options.length === feature.checks"
                        :id="'input-feature-' + feature.id"
                        class="form-check-input"
                        :indeterminate.prop="feature.checks !== 0 && feature.options.length > feature.checks"
                        @change="featureCheckboxChanged(feature, $event)"/>
                </span>
                <label :for="'input-feature-' + feature.id">
                    @{{ feature.title }}
                </label>
            </h4>

            {{-- Feature Options --}}
            <div class="c-option-list row">

                {{-- Feature Option Item --}}
                <div v-for="option of feature.options" class="c-option-item col-md-4 col-6">
                    <div class="c-option-item__input-wrapper form-check">

                        {{-- Feature Input --}}
                        <input :id="'input-option-' + option.value" type="checkbox"
                            :value="option.id"
                            :name="`options[${feature.id}][${option.id}]`"
                            class="form-check-input"
                            v-model="option.checked"
                            @change="optionCheckboxChanged(feature, option)" />

                        {{-- Feature Label --}}
                        <label :for="'input-option-' + option.value" class="form-check-label d-flex align-items-center">
                            <span v-if="feature.type === 'color'"
                                class="rounded me-2"
                                style="width: 20px; height: 20px;"
                                :style="{'background-color': option.color}"
                            ></span>
                            <span>
                                @{{ option.text }}
                            </span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="list-group-item">
            <button type="button" class="btn btn-primary btn-sm w-100"
                @click="saveGenerate(current)" :disabled="loading.generating">
                <span class="fa fa-save"></span>
                @{{ loading.generating ? '儲存中' : '建立組合' }}
            </button>
        </div>
    </div>
    <div v-else class="text-center card-body">
        載入中...
    </div>
</div>
</script>

<script>
    function variantGeneration() {
        const { ref, toRefs, reactive, computed, watch, onMounted, inject } = Vue;

        return {
            name: 'variantGeneration',
            template: u.selectOne('#c-variant-generation').innerHTML,
            props: {
                items: Array
            },
            setup(props, { emit }) {
                const state = reactive({
                    features: [],
                    loading: {
                        generating: false,
                        getFeatureOptions: false
                    }
                });

                const product = inject('product') || {};
                const mainPrice = inject('mainPrice');
                const currentHashes = computed(() => props.items.map(item => item.hash));

                onMounted(() => {
                    getFeatureOptions();
                });

                async function getFeatureOptions() {
                    state.loading.getFeatureOptions = true;

                    try {
                        const res = await u.$http.get('@product_ajax/getFeatureOptions');

                        state.features = ShopgoVueUtilities.prepareVueItemList(
                            res.data.data,
                            (feature) => {
                                feature.checks = 0;
                            }
                        );
                    } finally {
                        state.loading.getFeatureOptions = false;
                    }
                }

                const combinationCount = computed(() => {
                    return state.features.reduce((carry, feature) => {
                        return feature.checks > 0 ? carry * feature.checks : carry;
                    }, 1);
                });

                async function saveGenerate() {
                    // Prevent too many selected
                    if (combinationCount.value >= 100) {
                        u.alert(
                            '您選擇了過多選項', `這樣會產生 ${combinationCount.value} 個組合，系統與瀏覽器會無法負擔，請控制在 100 個以下`,
                            'warning'
                        );
                        return;
                    }

                    state.loading.generating = true;

                    try {
                        const res = await u.$http.post(
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
                        state.loading.generating = false;
                    }
                }

                function getCheckedOptionGroup() {
                    const data = {};

                    for (const feature of state.features) {
                        const options = feature.options
                            .filter(option => option.checked);

                        if (options.length > 0) {
                            data[feature.id] = options;
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

                function featureCheckboxChanged(feature, $event) {
                    feature.options.forEach(option => option.checked = $event.target.checked);
                    feature.checks = $event.target.checked ? feature.options.length : 0;
                }

                function optionCheckboxChanged(feature, option) {
                    feature.checks = 0;

                    feature.options.forEach(option => {
                        if (option.checked) {
                            feature.checks++;
                        }
                    });
                }

                function cancel() {
                    emit('cancel');
                }

                return {
                    ...toRefs(state),
                    combinationCount,

                    saveGenerate,
                    featureCheckboxChanged,
                    optionCheckboxChanged,
                    cancel,
                }
            }
        }
    }
</script>

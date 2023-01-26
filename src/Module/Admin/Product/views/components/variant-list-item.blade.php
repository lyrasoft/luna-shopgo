<?php

declare(strict_types=1);

namespace App\View;

use Unicorn\Image\ImagePlaceholder;
use Windwalker\Core\Application\AppContext;

/**
 * @var $app AppContext
 */

$defaultImage = $app->service(ImagePlaceholder::class)->placeholderSquare();
?>

<script id="c-variant-list-item" type="text/x-template">
<div class="list-group-item c-variant-item"
    :class="{ active }">
    <div class="list-group-item__wrapper d-flex align-items-center gap-2">
        <div class="c-variant-item__control d-flex flex-nowrap">
            <input type="checkbox"
                class="form-check-input"
                :checked="item.checked"
                @click="multiCheck" />
        </div>
        <div class="c-variant-item__image">
            <img :src="item.cover || '{{ $defaultImage }}'"
                width="45" height="45" alt="Cover" class="rounded">
        </div>
        <div class="c-variant-item__title flex-fill text-truncate">
            <div class="text-truncate" style="max-width: 100%">
                @{{ item.title }}
            </div>
            <div>
                <span v-if="item.sku"
                    style="opacity: .75">
                    #@{{ item.sku }}
                </span>

                <span v-if="priceOffset !== 0"
                    style="opacity: .75">
                    @{{ $offsetFormat(priceOffset, '$') }}
                </span>

                <span v-if="item.unsave"
                    class="badge bg-warning">
                    Unsave
                </span>

{{--                <span v-if="item.default === '1'" class="badge badge-info">--}}
{{--                    預設--}}
{{--                </span>--}}

{{--                <span v-if="item.saving" class="badge badge-warning">--}}
{{--                    更新中...--}}
{{--                </span>--}}
            </div>
        </div>
        <div class="c-variant-item__inventory text-end">
            @{{ $numberFormat(item.stockQuantity) }}
        </div>
        <div class="c-variant-item__actions d-flex flex-nowrap gap-1">
            <button type="button" class="btn btn-sm btn-light border-secondary"
                @click="edit" :disabled="item.saving">
                <span class="fa fa-pencil-alt"></span>
            </button>
            <button type="button" class="btn btn-sm btn-light border-secondary"
                @click="remove" :disabled="item.saving">
                <span class="fa fa-trash text-danger"></span>
            </button>
        </div>
    </div>
</div>
</script>

<script>
    function variantListItem() {
        const { ref, toRefs, reactive, computed, watch, inject } = Vue;

        return {
            name: 'VariantListItem',
            template: u.selectOne('#c-variant-list-item').innerHTML,
            props: {
                item: Object,
                i: Number,
                active: Boolean,
            },
            setup(props, { emit }) {
                function edit() {
                    emit('edit', props.item);
                }

                function remove() {
                    emit('remove', props.item);
                }

                function multiCheck($event) {
                    emit('oncheck', $event, props.i);
                }

                const mainPrice = inject('mainPrice');

                const priceOffset = computed(() => {
                    return Number(props.item.price) - Number(mainPrice.value);
                });

                return {
                    priceOffset,

                    multiCheck,
                    edit,
                    remove,
                }
            }
        }
    }
</script>

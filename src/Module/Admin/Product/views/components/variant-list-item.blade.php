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
            <input type="checkbox" v-model="item.checked"
                class="form-check-input"
                @click="multiCheck($event, item, i)" />
        </div>
        <div class="c-variant-item__image">
            <img :src="item.cover || '{{ $defaultImage }}'"
                width="45" height="45" alt="Cover" class="rounded">
        </div>
        <div class="c-variant-item__title flex-fill text-truncate">
            <div class="text-truncate" style="max-width: 100%">
                @{{ item.title }}
            </div>
            <div :class="[active ? 'text-light' : 'text-muted']">
                <span :class="[active === item.id ? 'text-light' : 'text-muted']">
                    #@{{ item.model }}
                </span>

{{--                <span v-if="item.default === '1'" class="badge badge-info">--}}
{{--                    預設--}}
{{--                </span>--}}

                <span v-if="item.saving" class="badge badge-warning">
                    更新中...
                </span>
            </div>
        </div>
        <div class="c-variant-item__inventory">
            <input type="number" v-model="item.stockQuantity" class="form-control form-control-sm"
                style="width: 75px;" @input="changeStock(item)" :disabled="item.saving" />
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

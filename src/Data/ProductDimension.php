<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Data;

use Windwalker\Data\RecordTrait;

/**
 * The ProductDimension class.
 */
#[\AllowDynamicProperties]
class ProductDimension
{
    use RecordTrait;

    public function __construct(
        public float $width = 0 {
            set(float|int|string $value) => $this->width = (float) $value;
        },
        public float $height = 0 {
            set(float|int|string $value) => $this->height = (float) $value;
        },
        public float $length = 0 {
            set(float|int|string $value) => $this->length = (float) $value;
        },
        public float $weight = 0 {
            set(float|int|string $value) => $this->weight = (float) $value;
        },
        public float $unitWeight = 0 {
            set(float|int|string $value) => $this->unitWeight = (float) $value;
        },
    ) {
    }
}

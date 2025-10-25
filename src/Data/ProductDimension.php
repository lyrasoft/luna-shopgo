<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Data;

use Windwalker\Data\RecordInterface;
use Windwalker\Data\RecordTrait;

/**
 * The ProductDimension class.
 */
#[\AllowDynamicProperties]
class ProductDimension implements RecordInterface
{
    use RecordTrait;

    public float $width = 0 {
        set(float|int|string $value) => $this->width = (float) $value;
    }

    public float $height = 0 {
        set(float|int|string $value) => $this->height = (float) $value;
    }

    public float $length = 0 {
        set(float|int|string $value) => $this->length = (float) $value;
    }

    public float $weight = 0 {
        set(float|int|string $value) => $this->weight = (float) $value;
    }

    public float $unitWeight = 0 {
        set(float|int|string $value) => $this->unitWeight = (float) $value;
    }

    public function __construct(
        float|int|string $width = 0,
        float|int|string $height = 0,
        float|int|string $length = 0,
        float|int|string $weight = 0,
        float|int|string $unitWeight = 0,
    ) {
        $this->unitWeight = $unitWeight;
        $this->weight = $weight;
        $this->length = $length;
        $this->height = $height;
        $this->width = $width;
    }
}

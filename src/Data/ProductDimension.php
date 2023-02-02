<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Data;

use Windwalker\Data\ValueObject;

/**
 * The ProductDimension class.
 */
class ProductDimension extends ValueObject
{
    public float $width = 0;
    public float $height = 0;
    public float $length = 0;
    public float $weight = 0;
    public float $unitWeight = 0;

    /**
     * @return float
     */
    public function getWidth(): float
    {
        return $this->width;
    }

    /**
     * @param  float|string  $width
     *
     * @return  static  Return self to support chaining.
     */
    public function setWidth(float|string $width): static
    {
        $this->width = (float) $width;

        return $this;
    }

    /**
     * @return float
     */
    public function getHeight(): float
    {
        return $this->height;
    }

    /**
     * @param  float|string  $height
     *
     * @return  static  Return self to support chaining.
     */
    public function setHeight(float|string $height): static
    {
        $this->height = (float) $height;

        return $this;
    }

    /**
     * @return float
     */
    public function getLength(): float
    {
        return $this->length;
    }

    /**
     * @param  float|string  $length
     *
     * @return  static  Return self to support chaining.
     */
    public function setLength(float|string $length): static
    {
        $this->length = (float) $length;

        return $this;
    }

    /**
     * @return float
     */
    public function getWeight(): float
    {
        return $this->weight;
    }

    /**
     * @param  float|string  $weight
     *
     * @return  static  Return self to support chaining.
     */
    public function setWeight(float|string $weight): static
    {
        $this->weight = (float) $weight;

        return $this;
    }

    /**
     * @return float
     */
    public function getUnitWeight(): float
    {
        return $this->unitWeight;
    }

    /**
     * @param  float|string  $unitWeight
     *
     * @return  static  Return self to support chaining.
     */
    public function setUnitWeight(float|string $unitWeight): static
    {
        $this->unitWeight = (float) $unitWeight;

        return $this;
    }
}

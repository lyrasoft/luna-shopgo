<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Data;

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
     * @param  float  $width
     *
     * @return  static  Return self to support chaining.
     */
    public function setWidth(float $width): static
    {
        $this->width = $width;

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
     * @param  float  $height
     *
     * @return  static  Return self to support chaining.
     */
    public function setHeight(float $height): static
    {
        $this->height = $height;

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
     * @param  float  $length
     *
     * @return  static  Return self to support chaining.
     */
    public function setLength(float $length): static
    {
        $this->length = $length;

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
     * @param  float  $weight
     *
     * @return  static  Return self to support chaining.
     */
    public function setWeight(float $weight): static
    {
        $this->weight = $weight;

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
     * @param  float  $unitWeight
     *
     * @return  static  Return self to support chaining.
     */
    public function setUnitWeight(float $unitWeight): static
    {
        $this->unitWeight = $unitWeight;

        return $this;
    }
}

<?php

/**
 * Part of toolstool project.
 *
 * @copyright  Copyright (C) 2022 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Data;

use JetBrains\PhpStorm\Deprecated;
use Windwalker\Data\ValueObject;

/**
 * The AddressData class.
 *
 * @deprecated  Currently not use this.
 */
#[\AllowDynamicProperties]
#[Deprecated('Currently not use this.')]
class AddressData extends ValueObject
{
    public int $locationId = 0;

    public string $address1 = '';

    public string $address2 = '';

    public string $fullAddress = '';

    /**
     * @return int
     */
    public function getLocationId(): int
    {
        return $this->locationId;
    }

    /**
     * @param  int  $locationId
     *
     * @return  static  Return self to support chaining.
     */
    public function setLocationId(int $locationId): static
    {
        $this->locationId = $locationId;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddress1(): string
    {
        return $this->address1;
    }

    /**
     * @param  string  $address1
     *
     * @return  static  Return self to support chaining.
     */
    public function setAddress1(string $address1): static
    {
        $this->address1 = $address1;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddress2(): string
    {
        return $this->address2;
    }

    /**
     * @param  string  $address2
     *
     * @return  static  Return self to support chaining.
     */
    public function setAddress2(string $address2): static
    {
        $this->address2 = $address2;

        return $this;
    }

    /**
     * @return string
     */
    public function getFullAddress(): string
    {
        return $this->fullAddress;
    }

    /**
     * @param  string  $fullAddress
     *
     * @return  static  Return self to support chaining.
     */
    public function setFullAddress(string $fullAddress): static
    {
        $this->fullAddress = $fullAddress;

        return $this;
    }
}

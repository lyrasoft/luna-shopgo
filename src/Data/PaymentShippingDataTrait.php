<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Data;

/**
 * Trait PaymentShippingDataTrait
 */
trait PaymentShippingDataTrait
{
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $mobile = '';
    public string $company = '';
    public string $vat = '';
    public int $addressId = 0;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param  string  $name
     *
     * @return  static  Return self to support chaining.
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param  string  $email
     *
     * @return  static  Return self to support chaining.
     */
    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param  string  $phone
     *
     * @return  static  Return self to support chaining.
     */
    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string
     */
    public function getMobile(): string
    {
        return $this->mobile;
    }

    /**
     * @param  string  $mobile
     *
     * @return  static  Return self to support chaining.
     */
    public function setMobile(string $mobile): static
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * @return int
     */
    public function getAddressId(): int
    {
        return $this->addressId;
    }

    /**
     * @param  int  $addressId
     *
     * @return  static  Return self to support chaining.
     */
    public function setAddressId(int $addressId): static
    {
        $this->addressId = $addressId;

        return $this;
    }

    /**
     * @return string
     */
    public function getCompany(): string
    {
        return $this->company;
    }

    /**
     * @param  string  $company
     *
     * @return  static  Return self to support chaining.
     */
    public function setCompany(string $company): static
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return string
     */
    public function getVat(): string
    {
        return $this->vat;
    }

    /**
     * @param  string  $vat
     *
     * @return  static  Return self to support chaining.
     */
    public function setVat(string $vat): static
    {
        $this->vat = $vat;

        return $this;
    }
}

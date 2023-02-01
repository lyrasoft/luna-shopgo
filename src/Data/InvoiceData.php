<?php

/**
 * Part of toolstool project.
 *
 * @copyright  Copyright (C) 2022 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyraoft\ShopGo\Data;

use Windwalker\Data\ValueObject;

/**
 * The InvoiceData class.
 */
class InvoiceData extends ValueObject
{
    public string $title = '';

    public string $vat = '';

    public string $no = '';

    public string $date = '';

    public string $address = '';

    public string $mobile = '';

    public string $carrierCode = '';

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param  string  $title
     *
     * @return  static  Return self to support chaining.
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;

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

    /**
     * @return string
     */
    public function getNo(): string
    {
        return $this->no;
    }

    /**
     * @param  string  $no
     *
     * @return  static  Return self to support chaining.
     */
    public function setNo(string $no): static
    {
        $this->no = $no;

        return $this;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @param  string  $date
     *
     * @return  static  Return self to support chaining.
     */
    public function setDate(string $date): static
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param  string  $address
     *
     * @return  static  Return self to support chaining.
     */
    public function setAddress(string $address): static
    {
        $this->address = $address;

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
     * @return string
     */
    public function getCarrierCode(): string
    {
        return $this->carrierCode;
    }

    /**
     * @param  string  $carrierCode
     *
     * @return  static  Return self to support chaining.
     */
    public function setCarrierCode(string $carrierCode): static
    {
        $this->carrierCode = $carrierCode;

        return $this;
    }
}

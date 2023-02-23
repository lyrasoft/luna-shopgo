<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Data;

use Lyrasoft\ShopGo\Data\Contract\AddressAwareInterface;
use Lyrasoft\ShopGo\Entity\Address;

/**
 * Trait AddressingDataTrait
 */
trait AddressAwaitTrait
{
    public string $name = '';
    public string $firstname = '';
    public string $lastname = '';
    public string $email = '';
    public string $phone = '';
    public string $mobile = '';
    public string $company = '';
    public string $country = '';
    public string $state = '';
    public string $city = '';
    public string $postcode = '';
    public string $address1 = '';
    public string $address2 = '';
    public string $vat = '';
    public string $formatted = '';
    public int $locationId = 0;
    public int $addressId = 0;

    /**
     * @return string
     */
    public function getName(): string
    {
        if ($this->name) {
            return $this->name;
        }

        return trim($this->firstname . ' ' . $this->lastname);
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

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param  string  $country
     *
     * @return  static  Return self to support chaining.
     */
    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @param  string  $state
     *
     * @return  static  Return self to support chaining.
     */
    public function setState(string $state): static
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param  string  $city
     *
     * @return  static  Return self to support chaining.
     */
    public function setCity(string $city): static
    {
        $this->city = $city;

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
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * @param  string  $lastname
     *
     * @return  static  Return self to support chaining.
     */
    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @param  string  $firstname
     *
     * @return  static  Return self to support chaining.
     */
    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * @return string
     */
    public function getPostcode(): string
    {
        return $this->postcode;
    }

    /**
     * @param  string  $postcode
     *
     * @return  static  Return self to support chaining.
     */
    public function setPostcode(string $postcode): static
    {
        $this->postcode = $postcode;

        return $this;
    }

    public function fillFrom(AddressAwareInterface $address): static
    {
        return $this->setLocationId($address->getLocationId())
            ->setFirstname($address->getFirstname())
            ->setLastname($address->getLastname())
            ->setEmail($address->getEmail())
            ->setPhone($address->getPhone())
            ->setMobile($address->getMobile())
            ->setCompany($address->getCompany())
            ->setVat($address->getVat())
            ->setAddress1($address->getAddress1())
            ->setAddress2($address->getAddress2())
            ->setPostcode($address->getPostcode())
            ->setCountry($address->getCountry())
            ->setState($address->getState())
            ->setCity($address->getCity())
            ->setName(trim($address->getFirstname() . ' ' . $address->getLastname()))
            ->setFormatted($address->getFormatted());
    }

    /**
     * @return string
     */
    public function getFormatted(): string
    {
        return $this->formatted;
    }

    /**
     * @param  string  $formatted
     *
     * @return  static  Return self to support chaining.
     */
    public function setFormatted(string $formatted): static
    {
        $this->formatted = $formatted;

        return $this;
    }
}

<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Data;

use Lyrasoft\ShopGo\Data\Contract\AddressAwareInterface;

/**
 * Trait AddressingDataTrait
 */
trait AddressAwaitTrait
{
    public string $name = '' {
        get {
            if ($this->name) {
                return $this->name;
            }

            return trim($this->firstname . ' ' . $this->lastname);
        }
    }

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

    public function fillFrom(AddressAwareInterface $address): static
    {
        $this->locationId = $address->locationId;
        $this->firstname = $address->firstname;
        $this->lastname = $address->lastname;
        $this->email = $address->email;
        $this->phone = $address->phone;
        $this->mobile = $address->mobile;
        $this->company = $address->company;
        $this->vat = $address->vat;
        $this->address1 = $address->address1;
        $this->address2 = $address->address2;
        $this->postcode = $address->postcode;
        $this->country = $address->country;
        $this->state = $address->state;
        $this->city = $address->city;
        $this->name = $address->name;
        $this->formatted = $address->formatted;

        return $this;
    }
}

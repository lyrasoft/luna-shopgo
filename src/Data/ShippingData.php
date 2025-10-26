<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Data;

use Lyrasoft\ShopGo\Data\Contract\AddressAwareInterface;
use Windwalker\Data\RecordInterface;
use Windwalker\Data\RecordTrait;

/**
 * The PaymentData class.
 */
#[\AllowDynamicProperties]
class ShippingData implements AddressAwareInterface, RecordInterface
{
    use AddressAwaitTrait;
    use RecordTrait;

    public bool $sync = false;

    public bool $save = false;

    function __construct(
        string $name = '',
        string $firstname = '',
        string $lastname = '',
        string $email = '',
        string $phone = '',
        string $mobile = '',
        string $company = '',
        string $country = '',
        string $state = '',
        string $city = '',
        string $postcode = '',
        string $address1 = '',
        string $address2 = '',
        string $vat = '',
        string $formatted = '',
        int $locationId = 0,
        public mixed $addressId = null,
        public string $shippingTitle = '',
        public string $note = '',
        mixed $save = false,
        mixed $sync = false,
    ) {
        $this->save = (bool) $save;
        $this->sync = (bool) $sync;
        $this->name = $name;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->phone = $phone;
        $this->mobile = $mobile;
        $this->company = $company;
        $this->country = $country;
        $this->state = $state;
        $this->city = $city;
        $this->postcode = $postcode;
        $this->address1 = $address1;
        $this->address2 = $address2;
        $this->vat = $vat;
        $this->formatted = $formatted;
        $this->locationId = $locationId;
    }
}

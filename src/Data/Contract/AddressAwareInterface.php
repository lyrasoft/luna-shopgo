<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Data\Contract;

/**
 * Interface AddressAwareInterface
 */
interface AddressAwareInterface
{
    public string $name { get; }
    public string $firstname { get; }
    public string $lastname { get; }
    public string $email { get; }
    public string $phone { get; }
    public string $mobile { get; }
    public string $company { get; }
    public string $country { get; }
    public string $state { get; }
    public string $city { get; }
    public string $postcode { get; }
    public string $address1 { get; }
    public string $address2 { get; }
    public string $vat { get; }
    public string $formatted { get; set; }
    public int $locationId { get; }
    public ?int $addressId { get; }

    /**
     * @param  AddressAwareInterface  $address
     *
     * @return  static
     */
    public function fillFrom(AddressAwareInterface $address): static;
}

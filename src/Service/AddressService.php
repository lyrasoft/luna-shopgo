<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Service;

use App\Data\Contract\AddressAwareInterface;
use App\Entity\Location;
use Windwalker\Utilities\SimpleTemplate;

/**
 * The AddressService class.
 */
class AddressService
{
    // phpcs:disable
    public const DEFAULT_FORMAT = "{firstname} {lastname}\r\n{company}\r\n{address1}\r\n{address2}\r\n{city}, {zone} {postcode}\r\n{country}";

    // phpcs:enable

    public static function format(
        AddressAwareInterface $addressData,
        ?string $format = null,
        bool $withName = false
    ): string {
        $format ??= static::DEFAULT_FORMAT;

        $data = [
            'email' => $addressData->getEmail(),
            'phone' => $addressData->getPhone(),
            'mobile' => $addressData->getMobile(),
            'country' => $addressData->getCountry(),
            'state' => $addressData->getState(),
            'city' => $addressData->getCity(),
            'address1' => $addressData->getAddress1(),
            'address2' => $addressData->getAddress2(),
        ];

        if ($withName) {
            $data = array_merge(
                [
                    [
                        'fullname' => $addressData->getFullName(),
                        'name' => $addressData->getFullName(),
                        'firstname' => $addressData->getFirstName(),
                        'lastname' => $addressData->getLastName(),
                        'company' => $addressData->getCompany(),
                        'vat' => $addressData->getVat(),
                    ],
                    $data,
                ]
            );
        }

        return trim(SimpleTemplate::render($format, $data, '.', ['{', '}']));
    }

    public static function formatByLocation(
        AddressAwareInterface $addressData,
        ?Location $location,
        bool $withName = false
    ): string {
        return static::format($addressData, $location?->getAddressFormat() ?: null, $withName);
    }
}

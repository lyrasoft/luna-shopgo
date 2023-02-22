<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Service;

use Lyrasoft\Luna\Entity\User;
use Lyrasoft\ShopGo\Data\Contract\AddressAwareInterface;
use Lyrasoft\ShopGo\Entity\Address;
use Lyrasoft\ShopGo\Entity\Location;
use Lyrasoft\ShopGo\Repository\AddressRepository;
use Windwalker\Data\Collection;
use Windwalker\DI\Attributes\Autowire;
use Windwalker\ORM\NestedSetMapper;
use Windwalker\ORM\ORM;
use Windwalker\Utilities\SimpleTemplate;

/**
 * The AddressService class.
 */
class AddressService
{
    // phpcs:disable
    public const DEFAULT_FORMAT = "{firstname} {lastname}\r\n{company}\r\n{address1}\r\n{address2}\r\n{city}, {zone} {postcode}\r\n{country}";

    // phpcs:enable
    public function __construct(#[Autowire] protected AddressRepository $repository, protected ORM $orm)
    {
    }

    /**
     * @param  User  $user
     *
     * @return  Collection<Address>
     *
     * @throws \ReflectionException
     */
    public function getUserAddresses(User $user): Collection
    {
        $addresses = $this->repository->getFrontListSelector()
            ->where('address.user_id', $user->getId())
            ->order('address.id', 'DESC')
            ->all(Address::class);

        /** @var NestedSetMapper<Location> $locationMapper */
        $locationMapper = $this->orm->mapper(Location::class);

        /** @var Address $address */
        foreach ($addresses as $address) {
            $location = $locationMapper->toEntity($address->location);
            $locationPath = $locationMapper->getPath($location);
            $locationPath->shift();

            $address->formatted = $address->formatByLocation($location, true);
            $address->locationPath = $locationPath->column('id')->values();
        }

        return $addresses;
    }

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
                    'fullname' => $addressData->getName(),
                    'name' => $addressData->getName(),
                    'firstname' => $addressData->getFirstname(),
                    'lastname' => $addressData->getLastname(),
                    'company' => $addressData->getCompany(),
                    'vat' => $addressData->getVat(),
                ],
                $data,
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

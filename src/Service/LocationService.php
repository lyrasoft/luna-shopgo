<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2022 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyraoft\ShopGo\Service;

use Lyraoft\ShopGo\Data\Contract\AddressAwareInterface;
use Lyraoft\ShopGo\Entity\Location;
use Lyraoft\ShopGo\Enum\LocationType;
use Windwalker\ORM\Nested\Position;
use Windwalker\ORM\NestedSetMapper;
use Windwalker\ORM\ORM;
use Windwalker\Utilities\Cache\InstanceCacheTrait;

/**
 * The LocationService class.
 */
class LocationService
{
    use InstanceCacheTrait;

    public function __construct(protected ORM $orm)
    {
    }

    public function formatAddress(AddressAwareInterface $addressData, bool $withName = false): string
    {
        $location = $this->getLocation($addressData->getLocationId());

        return AddressService::formatByLocation($addressData, $location, $withName);
    }

    /**
     * @param  Location  $location
     *
     * @return  array<Location|null>
     *
     * @throws \ReflectionException
     */
    public function getPathFromLocation(Location $location): array
    {
        $path = $this->getEntityMapper()->getPath($location);

        $country = null;
        $state = null;
        $city = null;

        foreach ($path as $loc) {
            if ($loc->getType() === LocationType::COUNTRY()) {
                $country = $loc;
                continue;
            }

            if ($loc->getType() === LocationType::STATE()) {
                $state = $loc;
                continue;
            }

            if ($loc->getType() === LocationType::CITY()) {
                $city = $loc;
                continue;
            }
        }

        return [$country, $state, $city];
    }

    public function getOrCreateContinent(string $title): Location
    {
        return $this->once(
            'continent.' . $title,
            function () use ($title) {
                $mapper = $this->getEntityMapper();

                $item = $mapper->findOne(
                    [
                        'title' => $title,
                        'type' => LocationType::CONTINENT()
                    ]
                );

                if (!$item) {
                    $item = $mapper->createEntity();
                    $item->setType(LocationType::CONTINENT());
                    $item->setTitle($title);

                    $mapper->setPosition($item, $this->getRoot()->getId(), Position::LAST_CHILD);
                    $item = $mapper->createOne($item);
                }

                return $item;
            }
        );
    }

    public function getCountry(string $title): Location
    {
        return $this->once(
            'country.' . $title,
            function () use ($title) {
                return $this->getEntityMapper()->mustFindOne(
                    [
                        'title' => $title,
                        'type' => LocationType::COUNTRY()
                    ]
                );
            }
        );
    }

    public function getRoot(): Location
    {
        return $this->cacheStorage['root'] ??= $this->getEntityMapper()->getRoot();
    }

    public function getLocation(int $id): ?Location
    {
        return $this->cacheStorage['location.' . $id]
            ??= $this->orm->findOne(Location::class, $id);
    }

    /**
     * @return  NestedSetMapper<Location>
     *
     * @throws \ReflectionException
     */
    public function getEntityMapper(): NestedSetMapper
    {
        return $this->orm->mapper(Location::class);
    }
}

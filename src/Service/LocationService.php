<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2022 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Service;

use Lyrasoft\ShopGo\Data\Contract\AddressAwareInterface;
use Lyrasoft\ShopGo\Entity\Location;
use Lyrasoft\ShopGo\Enum\LocationType;
use Lyrasoft\ShopGo\ShopGoPackage;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\DI\Container;
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
    use TranslatorTrait;

    public function __construct(protected ORM $orm, protected ShopGoPackage $shopGo, protected Container $container)
    {
    }

    public function formatAddress(AddressAwareInterface $addressData, bool $withName = false): string
    {
        [$country] = $this->getPathFromLocation($addressData->getLocationId());

        return AddressService::formatByLocation($addressData, $country, $withName);
    }

    /**
     * @param  Location|int  $location
     *
     * @return  array<Location|null>
     *
     * @throws \ReflectionException
     */
    public function getPathFromLocation(Location|int $location): array
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

    public function getSelectorLabels(): array
    {
        $labels = $this->shopGo->config('shipping.location_labels');

        if (!$labels) {
            return [
                $this->trans('shopgo.location.type.continent'),
                $this->trans('shopgo.location.type.country'),
                $this->trans('shopgo.location.type.state'),
                $this->trans('shopgo.location.type.city')
            ];
        }

        if (is_callable($labels)) {
            return $this->container->call($labels);
        }

        return $labels;
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

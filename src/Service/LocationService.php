<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2022 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Service;

use App\Entity\Location;
use App\Enum\LocationType;
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

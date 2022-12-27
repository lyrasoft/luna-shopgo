<?php

/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2022.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Migration;

use App\Entity\Location;
use App\Enum\LocationType;
use App\Service\LocationService;
use Lyrasoft\Luna\Services\LocaleService;
use Windwalker\Core\Console\ConsoleApplication;
use Windwalker\Core\Migration\Migration;
use Windwalker\Database\Schema\Schema;
use Windwalker\ORM\Nested\Position;
use Windwalker\ORM\NestedSetMapper;
use Windwalker\ORM\ORM;
use Windwalker\Stream\Stream;

use function Windwalker\chronos;

/**
 * Migration UP: 2022122708280011_LocationInit.
 *
 * @var Migration $mig
 * @var ConsoleApplication $app
 */
$mig->up(
    static function (ORM $orm) use ($mig, &$importLocations) {
        $mig->createTable(
            Location::class,
            function (Schema $schema) {
                $schema->primary('id');
                $schema->integer('category_id');
                $schema->integer('parent_id');
                $schema->char('type')->comment('LocationType: continent, country, state, city');
                $schema->integer('lft');
                $schema->integer('rgt');
                $schema->integer('level');
                $schema->varchar('region');
                $schema->varchar('subregion');
                $schema->varchar('title');
                $schema->varchar('native');
                $schema->char('code')->length(32);
                $schema->char('code3')->length(3);
                $schema->text('address_format');
                $schema->bool('postcode_required');
                $schema->bool('has_states');
                $schema->varchar('call_prefix');
                $schema->datetime('created');
                $schema->datetime('modified');
                $schema->integer('created_by');
                $schema->integer('modified_by');
                $schema->json('params')->nullable(true);

                $schema->addIndex('category_id');
                $schema->addIndex('parent_id');
                $schema->addIndex('type');
                $schema->addIndex('lft');
                $schema->addIndex('rgt');
                $schema->addIndex('level');
                $schema->addIndex('code');
                $schema->addIndex('code3');
            }
        );

        /** @var NestedSetMapper $mapper */
        $mapper = $orm->mapper(Location::class);
        $mapper->createRootIfNotExist(
            [
                'type' => LocationType::ROOT(),
            ]
        );

        $importLocations($orm);
    }
);

/**
 * Migration DOWN.
 */
$mig->down(
    static function () use ($mig) {
        // $mig->dropTableColumns(Table::class, 'column');
        $mig->dropTables(Location::class);
    }
);

$importLocations = function (ORM $orm) use ($mig, $app) {
    $locationService = $app->service(LocationService::class);
    $zipFile = __DIR__ . '/data/locations.zip';
    $countriesData = Stream::wrap("zip://$zipFile#countries.json", Stream::MODE_READ_ONLY_FROM_BEGIN)
        ->getContents();
    $zonesData = Stream::wrap("zip://$zipFile#zones.json", Stream::MODE_READ_ONLY_FROM_BEGIN)
        ->getContents();

    $countries = json_decode($countriesData, true);
    $zones = json_decode($zonesData, true);

    /** @var NestedSetMapper<Location> $mapper */
    $mapper = $orm->mapper(Location::class);

    $countriesMapping = [];

    $regions = array_unique(array_column($countries, 'region'));

    foreach ($regions as $region) {
        $locationService->getOrCreateContinent($region);
    }

    /**
     * @var array{
     *     id: int, region: string, subregion: string, title: string,
     *     native: string, code: string, code3: string, address_format: string,
     *     postcode_required: int, call_prefix: int, has_states: int
     *     } $country
     */
    foreach ($countries as $country) {
        $item = $mapper->createEntity();

        $continent = $locationService->getOrCreateContinent($country['region']);

        $item->setType(LocationType::COUNTRY());
        $item->setTitle($country['title']);
        $item->setNative($country['native']);
        $item->setRegion($country['region']);
        $item->setSubregion($country['subregion']);
        $item->setCode($country['code']);
        $item->setCode3($country['code3']);
        $item->setAddressFormat($country['address_format']);
        $item->setPostcodeRequired((bool) $country['postcode_required']);
        $item->setCallPrefix((string) $country['call_prefix']);
        $item->setHasStates((bool) $country['has_states']);

        $mapper->setPosition($item, $continent->getId(), Position::LAST_CHILD);

        $location = $mapper->createOne($item);

        $countriesMapping[$country['id']] = $location;

        $mig->outCounting();
    }

    foreach (array_chunk($zones, 500) as $zoneChunk) {
        $query = $orm->insert(Location::class);
        $query->columns('type', 'parent_id', 'title', 'code', 'address_format', 'created');

        /** @var array{ id: int, country_id: int, title: string, code: string } $zone */
        foreach ($zoneChunk as $zone) {
            /** @var Location $country */
            $country = $countriesMapping[$zone['country_id']];

            $query->values(
                [
                    LocationType::STATE(),
                    $country->getId(),
                    $zone['title'],
                    $zone['code'],
                    '',
                    chronos()
                ]
            );

            $mig->outCounting();
        }

        $query->execute();
    }

    $mapper->rebuild();
};

<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2022 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Seeder;

use App\Entity\Address;
use App\Entity\Location;
use Lyrasoft\Luna\Entity\User;
use Windwalker\Core\Seed\Seeder;
use Windwalker\Database\DatabaseAdapter;
use Windwalker\ORM\EntityMapper;
use Windwalker\ORM\ORM;

/**
 * Address Seeder
 *
 * @var Seeder $seeder
 * @var ORM $orm
 * @var DatabaseAdapter $db
 */
$seeder->import(
    static function () use ($seeder, $orm, $db) {
        $faker = $seeder->faker('en_US');

        /** @var EntityMapper<Address> $mapper */
        $mapper = $orm->mapper(Address::class);

        $userIds = $orm->findColumn(User::class, 'id')->dump();
        $locationIds = $orm->findColumn(Location::class, 'id')->dump();

        foreach (range(1, 100) as $i) {
            $item = $mapper->createEntity();

            $item->setUserId((int) $faker->randomElement($userIds));
            // $item->setLocationId((int) $faker->randomElement($locationIds));
            $item->setFirstname($faker->firstName());
            $item->setLastname($faker->lastName());
            $item->setFullname($item->getFirstname() . ' ' . $item->getLastname());
            $item->setCompany($faker->company());
            $item->setAddress1($faker->address());
            $item->setAddress2($faker->address());
            $item->setCity($faker->city());
            $item->setPostcode((string) random_int(100, 9999));
            $item->setPhone($faker->phoneNumber());
            $item->setMobile($faker->phoneNumber());
            $item->setVat((string) random_int(10000000, 99999999));
            $item->setDetails($faker->words());
            $item->setState($faker->optional(0.7, 0)->passthrough(1));
            $item->setCreated($faker->dateTimeThisYear());
            $item->setModified($item->getCreated()->modify('+10days'));

            $mapper->createOne($item);

            $seeder->outCounting();
        }
    }
);

$seeder->clear(
    static function () use ($seeder, $orm, $db) {
        $seeder->truncate(Address::class);
    }
);

<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2022 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Seeder;

use App\Entity\Shipping;
use Windwalker\Core\Seed\Seeder;
use Windwalker\Database\DatabaseAdapter;
use Windwalker\ORM\EntityMapper;
use Windwalker\ORM\ORM;
use Windwalker\Utilities\Utf8String;

/**
 * Shipping Seeder
 *
 * @var Seeder          $seeder
 * @var ORM             $orm
 * @var DatabaseAdapter $db
 */
$seeder->import(
    static function () use ($seeder, $orm, $db) {
        $faker = $seeder->faker('en_US');

        /** @var EntityMapper<Shipping> $mapper */
        $mapper = $orm->mapper(Shipping::class);

        foreach (range(1, 5) as $i) {
            $item = $mapper->createEntity();

            $item->setTitle(Utf8String::ucfirst($faker->word()) . ' Shipping');
            $item->setDescription($faker->paragraph());
            $item->setImage($faker->unsplashImage(400, 400));
            $item->setState(1);
            $item->setOrdering($i + 1);

            $mapper->createOne($item);

            $seeder->outCounting();
        }
    }
);

$seeder->clear(
    static function () use ($seeder, $orm, $db) {
        $seeder->truncate(Shipping::class);
    }
);
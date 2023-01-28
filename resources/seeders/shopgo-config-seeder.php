<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Seeder;

use Lyrasoft\Luna\Entity\Config;
use Lyrasoft\Luna\Entity\User;
use Windwalker\Core\Seed\Seeder;
use Windwalker\Database\DatabaseAdapter;
use Windwalker\ORM\EntityMapper;
use Windwalker\ORM\ORM;

/**
 * ShopgoConfig Seeder
 *
 * @var Seeder          $seeder
 * @var ORM             $orm
 * @var DatabaseAdapter $db
 */
$seeder->import(
    static function () use ($seeder, $orm, $db) {
        $faker = $seeder->faker('en_US');

        /** @var EntityMapper<Config> $mapper */
        $mapper = $orm->mapper(Config::class);

        $item = $mapper->createEntity();

        $item->setType('shopgo_shop');
        $item->setContent(
            [
                'currency_main' => 1
            ]
        );

        $mapper->createOne($item);

        $seeder->outCounting();
    }
);

$seeder->clear(
    static function () use ($seeder, $orm, $db) {
        //
    }
);

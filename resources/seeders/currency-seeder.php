<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2022 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Seeder;

use App\Entity\Currency;
use App\Enum\SignPosition;
use Lyrasoft\Luna\Entity\Language;
use Lyrasoft\Luna\Entity\User;
use Windwalker\Core\Seed\Seeder;
use Windwalker\Database\DatabaseAdapter;
use Windwalker\ORM\EntityMapper;
use Windwalker\ORM\ORM;

/**
 * Currency Seeder
 *
 * @var Seeder          $seeder
 * @var ORM             $orm
 * @var DatabaseAdapter $db
 */
$seeder->import(
    static function () use ($seeder, $orm, $db) {
        $faker = $seeder->faker('en_US');

        /** @var EntityMapper<Currency> $mapper */
        $mapper = $orm->mapper(Currency::class);

        $languages = $orm->findList(Language::class, ['state' => 1]);
        $userIds = $orm->findColumn(User::class, 'id')->dump();

        /** @var Language $language */
        foreach ($languages as $language) {
            $item = $mapper->createEntity();

            $item->setTitle($language->getTitleNative());
            $item->setCode($language->getCode());
            $item->setSign($faker->sentence(1));
            $item->setSignPosition($faker->randomElement(SignPosition::values()));
            $item->setDecimalPlace(random_int(1, 3));
            $item->setDecimalPoint('.');
            $item->setNumSeparator(',');
            $item->setExchangeRate((float) random_int(1, 30));
            $item->setSpace((bool) $faker->optional(0.5, 0)->passthrough(1));
            $item->setState($faker->optional(0.7, 0)->passthrough(1));
            $item->setCreated($faker->dateTimeThisYear());
            $item->setModified($item->getCreated()->modify('+10days'));
            $item->setCreatedBy((int) $faker->randomElement($userIds));
            $item->setParams([]);

            $mapper->createOne($item);

            $seeder->outCounting();
        }
    }
);

$seeder->clear(
    static function () use ($seeder, $orm, $db) {
        $seeder->truncate(Currency::class);
    }
);

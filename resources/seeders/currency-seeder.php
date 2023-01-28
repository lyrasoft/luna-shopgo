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

        $item = $mapper->createEntity();

        // USD
        $item->setTitle('USD');
        $item->setCode('USD');
        $item->setSign('$');
        $item->setSignPosition(SignPosition::START());
        $item->setDecimalPlace(2);
        $item->setDecimalPoint('.');
        $item->setNumSeparator(',');
        $item->setExchangeRate(1);
        $item->setSpace(false);
        $item->setState(1);

        $mapper->createOne($item);

        $seeder->outCounting();

        // TWD
        $item = $mapper->createEntity();

        $item->setTitle('TWD');
        $item->setCode('TWD');
        $item->setSign('$');
        $item->setSignPosition(SignPosition::START());
        $item->setDecimalPlace(0);
        $item->setDecimalPoint('.');
        $item->setNumSeparator(',');
        $item->setExchangeRate(35);
        $item->setSpace(false);
        $item->setState(1);

        $mapper->createOne($item);

        // EUR
        $item = $mapper->createEntity();

        $item->setTitle('EUR');
        $item->setCode('EUR');
        $item->setSign('€');
        $item->setSignPosition(SignPosition::END());
        $item->setDecimalPlace(0);
        $item->setDecimalPoint(',');
        $item->setNumSeparator('.');
        $item->setExchangeRate(0.92);
        $item->setSpace(false);
        $item->setState(1);

        $mapper->createOne($item);

        $seeder->outCounting();
    }
);

$seeder->clear(
    static function () use ($seeder, $orm, $db) {
        $seeder->truncate(Currency::class);
    }
);

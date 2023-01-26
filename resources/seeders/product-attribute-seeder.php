<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Seeder;

use App\Data\ListOption;
use App\Entity\ProductAttribute;
use App\Enum\ProductAttributeType;
use Windwalker\Core\Seed\Seeder;
use Windwalker\Database\DatabaseAdapter;
use Windwalker\ORM\EntityMapper;
use Windwalker\ORM\ORM;
use Windwalker\Utilities\StrNormalize;

use function Windwalker\tid;

/**
 * ProductAttribute Seeder
 *
 * @var Seeder          $seeder
 * @var ORM             $orm
 * @var DatabaseAdapter $db
 */
$seeder->import(
    static function () use ($seeder, $orm, $db) {
        $faker = $seeder->faker('en_US');

        /** @var EntityMapper<ProductAttribute> $mapper */
        $mapper = $orm->mapper(ProductAttribute::class);

        foreach (range(1, 10) as $i) {
            $type = $faker->randomElement(ProductAttributeType::values());

            $item = $mapper->createEntity();
            $item->setType($type);
            $item->setTitle($faker->sentence(1));
            $item->setKey(
                StrNormalize::toSnakeCase(trim($item->getTitle(), '.'))
            );
            $item->setOrdering($i);
            $item->setState(1);

            $options = [];

            if ($type === ProductAttributeType::SELECT()) {
                foreach (range(1, random_int(5, 8)) as $o) {
                    $options[] = new ListOption(
                        [
                            'uid' => tid(),
                            'text' => $text = $faker->word(),
                            'value' => strtolower($text)
                        ]
                    );
                }
            }

            $item->setOptions($options);

            $mapper->createOne($item);

            $seeder->outCounting();
        }
    }
);

$seeder->clear(
    static function () use ($seeder, $orm, $db) {
        $seeder->truncate(ProductAttribute::class);
    }
);

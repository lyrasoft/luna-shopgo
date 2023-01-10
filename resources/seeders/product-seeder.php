<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2022 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Seeder;

use App\Entity\Product;
use App\Entity\ShopCategoryMap;
use Lyrasoft\Luna\Entity\Category;
use Unicorn\Utilities\SlugHelper;
use Windwalker\Core\Seed\Seeder;
use Windwalker\Database\DatabaseAdapter;
use Windwalker\ORM\EntityMapper;
use Windwalker\ORM\ORM;
use Windwalker\Utilities\Utf8String;

/**
 * Product Seeder
 *
 * @var Seeder $seeder
 * @var ORM $orm
 * @var DatabaseAdapter $db
 */
$seeder->import(
    static function () use ($seeder, $orm, $db) {
        $faker = $seeder->faker('en_US');

        /** @var EntityMapper<Product> $mapper */
        $mapper = $orm->mapper(Product::class);

        /** @var EntityMapper<ShopCategoryMap> $mapMapper */
        $mapMapper = $orm->mapper(ShopCategoryMap::class);

        $categoryIds = $orm->findColumn(Category::class, 'id', ['type' => 'product'])->dump();

        foreach (range(1, 100) as $i) {
            $item = $mapper->createEntity();

            $item->setCategoryId((int) $faker->randomElement($categoryIds));
            // $item->setPrimaryVariantId();
            $item->setModel($faker->sentence(1));
            $item->setTitle(
                Utf8String::ucwords(
                    $faker->sentence(1)
                )
            );
            $item->setAlias(SlugHelper::safe($item->getTitle()));
            $item->setOriginPrice((float) $faker->randomElement([500, 1000, 1500, 2000, 2500, 3000, 3500]));
            $item->setSafeQuantity(random_int(5, 20));
            $item->setIntro($faker->paragraph(2));
            $item->setDescription($faker->paragraph(5));
            $item->setMeta(
                [
                    'title' => $item->getTitle(),
                    'description' => $item->getDescription(),
                    'keywords' => implode(',', $faker->words()),
                ]
            );
            $item->setCanAttach((bool) $faker->optional(0.1, 0)->passthrough(1));
            // $item->setVariants();
            $item->setOrdering((int) $i);
            // $item->setHide();
            $item->setState($faker->optional(0.7, 0)->passthrough(1));
            // $item->setSearchIndex();
            // $item->setShippings();
            $item->setCreated($faker->dateTimeThisYear());
            $item->setModified($item->getCreated()->modify('+10days'));
            $item->setCreatedBy(1);
            $item->setHits(random_int(1, 9999));
            $item->setParams([]);

            $item = $mapper->createOne($item);

            $catelogIds = array_filter(
                $categoryIds,
                static fn($v) => $v !== $item->getCategoryId()
            );

            // Primary category
            $map = $mapMapper->createEntity();

            $map->setType('product');
            $map->setTargetId($item->getId());
            $map->setCategoryId($item->getCategoryId());
            $map->setPrimary(true);
            $map->setOrdering(1);

            $mapMapper->createOne($map);

            // Sub categories
            foreach ($faker->randomElements($catelogIds, 3) as $k => $catelogId) {
                $map = $mapMapper->createEntity();

                $map->setType('product');
                $map->setTargetId($item->getId());
                $map->setCategoryId((int) $catelogId);
                $map->setOrdering($k + 2);

                $mapMapper->createOne($map);
            }
        }
    }
);

$seeder->clear(
    static function () use ($seeder, $orm, $db) {
        $seeder->truncate(Product::class);
        $seeder->truncate(ShopCategoryMap::class);
    }
);

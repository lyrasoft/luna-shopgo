<?php

/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2022.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Migration;

use App\Entity\ShopCategoryMap;
use Windwalker\Core\Console\ConsoleApplication;
use Windwalker\Core\Migration\Migration;
use Windwalker\Database\Schema\Schema;

/**
 * Migration UP: 2022122704260003_ShopInit.
 *
 * @var Migration $mig
 * @var ConsoleApplication $app
 */
$mig->up(
    static function () use ($mig) {
        // $mig->updateTable(
        //     Table::class,
        //     function (Schema $schema) {}
        // );
        $mig->createTable(
            ShopCategoryMap::class,
            function (Schema $schema) {
                $schema->primary('id');
                $schema->varchar('type')->comment('No enum - product,attribute,tab');
                $schema->integer('target_id');
                $schema->integer('category_id');
                $schema->integer('ordering');

                $schema->addIndex('type');
                $schema->addIndex('target_id');
                $schema->addIndex('category_id');
                $schema->addIndex('ordering');
            }
        );
    }
);

/**
 * Migration DOWN.
 */
$mig->down(
    static function () use ($mig) {
        // $mig->dropTableColumns(Table::class, 'column');
        $mig->dropTables(ShopCategoryMap::class);
    }
);

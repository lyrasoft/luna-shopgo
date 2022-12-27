<?php

/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2022.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Migration;

use App\Entity\Shipping;
use Windwalker\Core\Console\ConsoleApplication;
use Windwalker\Core\Migration\Migration;
use Windwalker\Database\Schema\Schema;

/**
 * Migration UP: 2022122704260012_ShippingInit.
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
            Shipping::class,
            function (Schema $schema) {
                $schema->primary('id');
                $schema->integer('location_category_id');
                $schema->integer('location_id');
                $schema->varchar('classname');
                $schema->varchar('type');
                $schema->varchar('title');
                $schema->longtext('description');
                $schema->varchar('image');
                $schema->json('payments')->nullable(true);
                $schema->json('pricing')->nullable(true);
                $schema->integer('state');
                $schema->integer('ordering');
                $schema->datetime('created');
                $schema->datetime('modified');
                $schema->integer('created_by');
                $schema->integer('modified_by');
                $schema->json('params')->nullable(true);

                $schema->addIndex('location_category_id');
                $schema->addIndex('location_id');
                $schema->addIndex('type');
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
        $mig->dropTables(Shipping::class);
    }
);

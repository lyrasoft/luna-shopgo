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
use Windwalker\Core\Console\ConsoleApplication;
use Windwalker\Core\Migration\Migration;
use Windwalker\Database\Schema\Schema;

/**
 * Migration UP: 2022122704260011_LocationInit.
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
                $schema->char('code')->length(2);
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

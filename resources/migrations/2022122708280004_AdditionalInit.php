<?php

/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2022.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Migration;

use App\Entity\AdditionalPurchasis;
use Windwalker\Core\Console\ConsoleApplication;
use Windwalker\Core\Migration\Migration;
use Windwalker\Database\Schema\Schema;

/**
 * Migration UP: 2022122708280004_AdditionalInit.
 *
 * @var Migration          $mig
 * @var ConsoleApplication $app
 */
$mig->up(
    static function () use ($mig) {
        $mig->createTable(
            AdditionalPurchasis::class,
            function (Schema $schema) {
                $schema->primary('id');
                $schema->integer('attach_product_id');
                $schema->integer('attach_variant_id');
                $schema->integer('primary_product_id');
                $schema->decimal('price')->length('20,4');

                $schema->addIndex('attach_product_id');
                $schema->addIndex('attach_variant_id');
                $schema->addIndex('primary_product_id');
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
        $mig->dropTables(AdditionalPurchasis::class);
    }
);

<?php

/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2022.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Migration;

use App\Entity\Currency;
use Windwalker\Core\Console\ConsoleApplication;
use Windwalker\Core\Migration\Migration;
use Windwalker\Database\Schema\Schema;

/**
 * Migration UP: 2022122708280007_CurrencyInit.
 *
 * @var Migration          $mig
 * @var ConsoleApplication $app
 */
$mig->up(
    static function () use ($mig) {
        $mig->createTable(
            Currency::class,
            function (Schema $schema) {
                $schema->primary('id');
                $schema->varchar('title');
                $schema->char('code')->length(3);
                $schema->integer('code_num');
                $schema->varchar('sign');
                $schema->char('sign_position')->length(5)->comment('SignPosition: start,end');
                $schema->tinyint('decimal_place');
                $schema->varchar('decimal_point');
                $schema->varchar('num_separator');
                $schema->decimal('exchange_rate')->length('20,4');
                $schema->bool('space');
                $schema->bool('state');
                $schema->datetime('created');
                $schema->datetime('modified');
                $schema->integer('created_by');
                $schema->integer('modified_by');
                $schema->json('params')->nullable(true);

                $schema->addIndex('code');
                $schema->addIndex('code_num');
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
        $mig->dropTables(Currency::class);
    }
);

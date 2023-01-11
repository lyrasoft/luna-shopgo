<?php

/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2023.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Migration;

use Windwalker\Core\Console\ConsoleApplication;
use Windwalker\Core\Migration\Migration;
use Windwalker\Database\Schema\Schema;

/**
 * Migration UP: 2023011103140001_ProductFeatureInit.
 *
 * @var Migration          $mig
 * @var ConsoleApplication $app
 */
$mig->up(
    static function () use ($mig) {
        // $mig->updateTable(
        //     Table::class,
        //     function (Schema $schema) {}
        // );
    }
);

/**
 * Migration DOWN.
 */
$mig->down(
    static function () use ($mig) {
        // $mig->dropTableColumns(Table::class, 'column');
    }
);

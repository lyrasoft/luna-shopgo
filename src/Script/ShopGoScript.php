<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Script;

use Windwalker\Core\Asset\AbstractScript;

/**
 * The ShopGoScript class.
 */
class ShopGoScript extends AbstractScript
{
    public function utilities(): void
    {
        if ($this->available()) {
            $this->js('js/shopgo/shopgo-utilities.js');
        }
    }
}

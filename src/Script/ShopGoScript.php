<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyraoft\ShopGo\Script;

use Windwalker\Core\Asset\AbstractScript;

/**
 * The ShopGoScript class.
 */
class ShopGoScript extends AbstractScript
{
    public function vueUtilities(): void
    {
        if ($this->available()) {
            $this->js('@shopgo/shopgo-vue-utilities.js');
        }
    }
}

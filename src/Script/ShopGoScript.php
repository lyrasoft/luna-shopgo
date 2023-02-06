<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Script;

use Lyrasoft\ShopGo\Service\CurrencyService;
use Unicorn\Script\UnicornScript;
use Windwalker\Core\Asset\AbstractScript;

/**
 * The ShopGoScript class.
 */
class ShopGoScript extends AbstractScript
{
    public function __construct(protected CurrencyService $currencyService, protected UnicornScript $unicornScript)
    {
    }

    public function vueUtilities(): void
    {
        if ($this->available()) {
            $this->currency();

            $this->js('@shopgo/shopgo-vue-utilities.js');
        }
    }

    public function currency(): void
    {
        if ($this->available()) {
            $currency = $this->currencyService->getCurrentCurrency();

            $this->unicornScript->data('currency', $currency);

            $this->js('@shopgo/currency.js');
        }
    }
}

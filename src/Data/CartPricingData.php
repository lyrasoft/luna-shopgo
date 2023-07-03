<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Data;

use Lyrasoft\ShopGo\Data\Contract\CartTotalsInterface;
use Lyrasoft\ShopGo\Data\Traits\CartDataAwareTrait;

/**
 * The CartPricingData class.
 */
#[\AllowDynamicProperties]
class CartPricingData implements CartTotalsInterface
{
    use CartDataAwareTrait;
}

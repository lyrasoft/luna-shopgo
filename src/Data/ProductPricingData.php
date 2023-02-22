<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Data;

use Lyrasoft\ShopGo\Data\Contract\ProductPricingInterface;
use Lyrasoft\ShopGo\Data\Traits\ProductPricingTrait;

/**
 * The ProductPricingData class.
 */
class ProductPricingData implements ProductPricingInterface
{
    use ProductPricingTrait;
}

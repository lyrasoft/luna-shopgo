<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Data;

use Lyrasoft\ShopGo\Data\Contract\ProductPricingInterface;
use Lyrasoft\ShopGo\Data\Traits\ProductPricingTrait;

/**
 * The ProductPricingData class.
 */
#[\AllowDynamicProperties]
class ProductPricingData implements ProductPricingInterface
{
    use ProductPricingTrait;
}

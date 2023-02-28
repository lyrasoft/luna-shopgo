<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Shipping;

/**
 * The PriceRange class.
 */
class PriceRange
{
    public const COMPUTE_UNIT_PER_ITEM = 'per_item';

    public const COMPUTE_UNIT_PER_ORDER = 'per_order';

    public const DEPENDS_ON_PRICE = 'price';

    public const DEPENDS_ON_WEIGHT = 'weight';
}

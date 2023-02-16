<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Event;

use Lyrasoft\ShopGo\Data\Contract\CartTotalsInterface;
use Lyrasoft\ShopGo\Data\Traits\CartTotalsTrait;
use Windwalker\Event\AbstractEvent;

/**
 * The AbstractComputeOrderTotalsEvent class.
 */
abstract class AbstractComputeTotalsEvent extends AbstractEvent implements CartTotalsInterface
{
    use CartTotalsTrait;
}

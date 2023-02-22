<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Event;

use Lyrasoft\ShopGo\Data\Contract\CartTotalsInterface;
use Lyrasoft\ShopGo\Data\Traits\CartDataAwareTrait;
use Windwalker\Event\AbstractEvent;

/**
 * The AbstractComputeOrderTotalsEvent class.
 */
abstract class AbstractCartDataEvent extends AbstractEvent implements CartTotalsInterface
{
    use CartDataAwareTrait;
}

<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Event;

use Lyrasoft\ShopGo\Event\Traits\OrderCreateEventTrait;
use Windwalker\Event\AbstractEvent;

/**
 * The AfterOrderCreateEvent class.
 */
class AfterOrderCreateEvent extends AbstractEvent
{
    use OrderCreateEventTrait;
}

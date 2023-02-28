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
 * The BeforeOrderCreateEvent class.
 */
class BeforeOrderCreateEvent extends AbstractEvent
{
    use OrderCreateEventTrait;
}

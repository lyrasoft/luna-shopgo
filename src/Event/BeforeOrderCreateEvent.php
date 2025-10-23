<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Event;

use Lyrasoft\ShopGo\Event\Traits\OrderCreateEventTrait;
use Windwalker\Event\AbstractEvent;
use Windwalker\Event\BaseEvent;

/**
 * The BeforeOrderCreateEvent class.
 */
class BeforeOrderCreateEvent extends BaseEvent
{
    use OrderCreateEventTrait;
}

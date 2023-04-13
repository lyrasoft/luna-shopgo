<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Shipping;

use Lyrasoft\ShopGo\Entity\Order;

/**
 * Interface ShipmentCreatingInterface
 */
interface ShipmentCreatingInterface
{
    /**
     * Create shipment for specify order.
     *
     * @param  Order  $order
     *
     * @return  void
     */
    public function createShipment(Order $order): void;
}

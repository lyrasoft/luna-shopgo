<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Shipping;

use Lyrasoft\ShopGo\Entity\Order;
use Windwalker\Core\Application\ApplicationInterface;

/**
 * Interface ShipmentPrintingInterface
 */
interface ShipmentPrintableInterface
{
    /**
     * Batch print multiple shipments.
     *
     * @param  ApplicationInterface  $app
     * @param  iterable<Order>       $orders
     *
     * @return  mixed Return response, uri or text.
     */
    public function printShipments(ApplicationInterface $app, iterable $orders): mixed;
}

<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Shipping;

use Lyrasoft\ShopGo\Entity\Order;

/**
 * Interface ShippingStatusAwareInterface
 */
interface ShippingStatusInterface
{
    /**
     * Update single order's status.
     *
     * @param  Order  $order
     *
     * @return  void
     */
    public function updateShippingStatus(Order $order): void;
}

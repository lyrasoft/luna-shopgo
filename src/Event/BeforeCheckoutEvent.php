<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Event;

use Lyrasoft\ShopGo\Entity\Order;
use Windwalker\Event\BaseEvent;

/**
 * The BeforeCheckoutEvent class.
 */
class BeforeCheckoutEvent extends BaseEvent
{
    public function __construct(
        public Order $order,
        public array $payment,
        public array $shipping,
        public array $paymentData,
        public array $shippingData,
        public array $input,
        public bool $overridePaymentDataProcess = false,
        public bool $overrideShippingDataProcess = false,
    ) {
    }
}

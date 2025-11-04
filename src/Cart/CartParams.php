<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Cart;

use Windwalker\Data\RecordTrait;

class CartParams
{
    use RecordTrait;

    public function __construct(
        public mixed $shippingId = null,
        public mixed $paymentId = null,
        public mixed $locationId = null,
    ) {
    }
}

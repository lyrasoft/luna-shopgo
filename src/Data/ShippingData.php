<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Data;

use Lyrasoft\ShopGo\Data\Contract\AddressAwareInterface;
use Windwalker\Data\RecordInterface;
use Windwalker\Data\RecordTrait;

/**
 * The PaymentData class.
 */
#[\AllowDynamicProperties]
class ShippingData implements AddressAwareInterface, RecordInterface
{
    use AddressAwaitTrait;
    use RecordTrait;

    public function __construct(
        public string $shippingTitle = '',
        public string $note = ''
    ) {
    }
}

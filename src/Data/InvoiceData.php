<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Data;

use Windwalker\Data\RecordTrait;

/**
 * The InvoiceData class.
 */
#[\AllowDynamicProperties]
class InvoiceData
{
    use RecordTrait;

    public function __construct(
        public string $title = '',
        public string $vat = '',
        public string $no = '',
        public string $date = '',
        public string $address = '',
        public string $mobile = '',
        public string $carrierCode = '',
    ) {
    }
}

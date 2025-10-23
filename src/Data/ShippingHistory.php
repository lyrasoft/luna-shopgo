<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Data;

use Windwalker\Core\DateTime\Chronos;
use Windwalker\Data\RecordInterface;
use Windwalker\Data\RecordTrait;
use Windwalker\ORM\Attributes\CastNullable;

/**
 * The ShippingHistory class.
 */
#[\AllowDynamicProperties]
class ShippingHistory implements RecordInterface
{
    use RecordTrait;

    public function __construct(
        public string $statusCode = '',
        public string $statusText = '',
        public string $note = '',
        #[CastNullable(Chronos::class)]
        public ?Chronos $time = null
    ) {
    }
}

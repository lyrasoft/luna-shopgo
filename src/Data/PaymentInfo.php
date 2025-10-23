<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Data;

use Windwalker\Core\DateTime\Chronos;
use Windwalker\Data\RecordInterface;
use Windwalker\Data\RecordTrait;
use Windwalker\ORM\Attributes\CastNullable;

/**
 * The PaymentInfo class.
 */
#[\AllowDynamicProperties]
class PaymentInfo implements RecordInterface
{
    use RecordTrait;

    public function __construct(
        public string $tradeNo = '',
        public string $transactionNo = '',
        public string $type = '',
        public string $amount = '0',
        public string $currency = '',
        public bool $isCod = false,
        #[CastNullable(Chronos::class)]
        public ?Chronos $created = null,
        #[CastNullable(Chronos::class)]
        public ?Chronos $expired = null,
    ) {
    }
}

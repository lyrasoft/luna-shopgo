<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Currency;

use Lyrasoft\ShopGo\Enum\SignPosition;
use Windwalker\Utilities\Options\RecordOptionsTrait;

class CurrencyOptions
{
    use RecordOptionsTrait;

    public function __construct(
        public ?bool $code = null,
        public ?bool $sign = null,
        public ?SignPosition $signPosition = null,
    ) {
    }
}

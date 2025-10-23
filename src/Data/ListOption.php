<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Data;

use Windwalker\Data\RecordTrait;

/**
 * The ListOption class.
 */
#[\AllowDynamicProperties]
class ListOption
{
    use RecordTrait;

    public function __construct(
        public string $value = '',
        public string $text = '',
        public string $uid = '',
        public string $color = '',
        public int $parentId = 0,
    ) {
    }
}

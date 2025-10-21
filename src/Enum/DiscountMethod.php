<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Enum;

use Windwalker\Utilities\Contract\LanguageInterface;
use Windwalker\Utilities\Enum\EnumTranslatableInterface;
use Windwalker\Utilities\Enum\EnumTranslatableTrait;

enum DiscountMethod: string implements EnumTranslatableInterface
{
    use EnumTranslatableTrait;

    case NONE = 'none';
    case OFFSETS = 'offsets';
    case FIXED = 'fixed';
    case PERCENTAGE = 'percentage';

    public function trans(LanguageInterface $lang, ...$args): string
    {
        return $lang->trans('shopgo.discount.method.' . $this->name);
    }
}

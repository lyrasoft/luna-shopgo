<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Enum;

use Windwalker\Utilities\Contract\LanguageInterface;
use Windwalker\Utilities\Enum\EnumTranslatableInterface;
use Windwalker\Utilities\Enum\EnumTranslatableTrait;

enum DiscountApplyTo: string implements EnumTranslatableInterface
{
    use EnumTranslatableTrait;

    case ORDER = 'order';
    case PRODUCTS = 'products';
    case MATCHED = 'matched';

    public static function preprocessValue(mixed $value): mixed
    {
        return $value ?: self::MATCHED;
    }

    public function trans(LanguageInterface $lang, ...$args): string
    {
        return $lang->trans('shopgo.discount.apply.to.' . $this->getKey());
    }
}

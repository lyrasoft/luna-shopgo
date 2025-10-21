<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Enum;

use Windwalker\Utilities\Contract\LanguageInterface;
use Windwalker\Utilities\Enum\EnumTranslatableInterface;
use Windwalker\Utilities\Enum\EnumTranslatableTrait;

enum DiscountCombine: string implements EnumTranslatableInterface
{
    use EnumTranslatableTrait;

    case OPEN = 'open';
    case STOP = 'stop';
    case INCLUDES = 'includes';
    case EXCLUDES = 'excludes';

    public static function preprocessValue(mixed $value): mixed
    {
        return $value ?: self::OPEN;
    }

    public function trans(LanguageInterface $lang, ...$args): string
    {
        return $lang->trans('shopgo.discount.combine.' . $this->getKey());
    }
}

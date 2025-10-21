<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Enum;

use Windwalker\Utilities\Contract\LanguageInterface;
use Windwalker\Utilities\Enum\EnumTranslatableInterface;
use Windwalker\Utilities\Enum\EnumTranslatableTrait;

enum DiscountType: string implements EnumTranslatableInterface
{
    use EnumTranslatableTrait;

    case PRODUCT = 'product';
    case GLOBAL = 'global';
    case COUPON = 'coupon';

    public function trans(LanguageInterface $lang, ...$args): string
    {
        return $lang->trans('shopgo.discount.type.' . $this->name);
    }

    public function getSubTypes(): array
    {
        return match ($this) {
            self::PRODUCT => [
                'discount',
                'special',
            ],
            self::GLOBAL => [
                'basic',
                'code',
            ],
            self::COUPON => [
                'basic',
            ],
        };
    }
}

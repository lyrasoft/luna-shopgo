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
        return $lang->trans('shopgo.discount.type.' . $this->getKey());
    }

    public function getSubTypes(): array
    {
        return match ($this->getValue()) {
            static::PRODUCT => [
                'discount',
                'special',
            ],
            static::GLOBAL => [
                'basic',
                'code',
            ],
            static::COUPON => [
                'basic',
            ],
        };
    }
}

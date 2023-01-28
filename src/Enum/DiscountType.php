<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Enum;

use MyCLabs\Enum\Enum;
use Windwalker\Utilities\Enum\EnumSingleton;
use Windwalker\Utilities\Enum\EnumTranslatableInterface;
use Windwalker\Utilities\Enum\EnumTranslatableTrait;
use Windwalker\Utilities\Contract\LanguageInterface;

/**
 * The DiscountType enum class.
 *
 * @method static $this PRODUCT()
 * @method static $this GLOBAL()
 * @method static $this COUPON()
 */
class DiscountType extends EnumSingleton implements EnumTranslatableInterface
{
    use EnumTranslatableTrait;

    public const PRODUCT = 'product';

    public const GLOBAL = 'global';

    public const COUPON = 'coupon';

    /**
     * Unable to directly new this object.
     *
     * @param  mixed  $value
     *
     * @throws \UnexpectedValueException if incompatible type is given.
     */
    protected function __construct(mixed $value)
    {
        parent::__construct($value);
    }

    public function trans(LanguageInterface $lang, ...$args): string
    {
        return $lang->trans('shopgo.discount.type.' . $this->getKey());
    }

    /**
     * @return  string[]
     */
    public function getSubTypes(): array
    {
        return match ($this->getValue()) {
            static::PRODUCT => [
                'discount',
                'special'
            ],
            static::GLOBAL => [
                'basic',
                'code'
            ],
            static::COUPON => [
                'basic'
            ],
        };
    }
}

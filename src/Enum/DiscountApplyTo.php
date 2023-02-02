<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Enum;

use Windwalker\Utilities\Contract\LanguageInterface;
use Windwalker\Utilities\Enum\EnumSingleton;
use Windwalker\Utilities\Enum\EnumTranslatableInterface;
use Windwalker\Utilities\Enum\EnumTranslatableTrait;

/**
 * The DiscountApplyTo enum class.
 *
 * @method static $this ORDER()
 * @method static $this PRODUCTS()
 * @method static $this MATCHED()
 */
class DiscountApplyTo extends EnumSingleton implements EnumTranslatableInterface
{
    use EnumTranslatableTrait;

    public const ORDER = 'order';

    public const PRODUCTS = 'products';

    public const MATCHED = 'matched';

    /**
     * Unable to directly new this object.
     *
     * @param  mixed  $value
     *
     * @throws \UnexpectedValueException if incompatible type is given.
     */
    protected function __construct(mixed $value)
    {
        parent::__construct($value ?: static::MATCHED());
    }

    public function trans(LanguageInterface $lang, ...$args): string
    {
        return $lang->trans('shopgo.discount.apply.to.' . $this->getKey());
    }
}

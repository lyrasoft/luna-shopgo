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
 * The DiscountMethod enum class.
 *
 * @method static $this NONE()
 * @method static $this OFFSETS()
 * @method static $this FIXED()
 * @method static $this PERCENTAGE()
 */
class DiscountMethod extends EnumSingleton implements EnumTranslatableInterface
{
    use EnumTranslatableTrait;

    public const NONE = 'none';

    public const OFFSETS = 'offsets';

    public const FIXED = 'fixed';

    public const PERCENTAGE = 'percentage';

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
        return $lang->trans('shopgo.discount.method.' . $this->getKey());
    }
}

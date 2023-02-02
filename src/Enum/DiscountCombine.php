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
 * The DiscountCombine enum class.
 *
 * @method static $this OPEN()
 * @method static $this STOP()
 * @method static $this INCLUDES()
 * @method static $this EXCLUDES()
 */
class DiscountCombine extends EnumSingleton implements EnumTranslatableInterface
{
    use EnumTranslatableTrait;

    public const OPEN = 'open';
    public const STOP = 'stop';
    public const INCLUDES = 'includes';
    public const EXCLUDES = 'excludes';

    /**
     * Unable to directly new this object.
     *
     * @param  mixed  $value
     *
     * @throws \UnexpectedValueException if incompatible type is given.
     */
    protected function __construct(mixed $value)
    {
        parent::__construct($value ?: static::OPEN);
    }

    public function trans(LanguageInterface $lang, ...$args): string
    {
        return $lang->trans('shopgo.discount.combine.' . $this->getKey());
    }
}

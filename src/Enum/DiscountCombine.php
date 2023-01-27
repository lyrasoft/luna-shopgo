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
 * The DiscountCombine enum class.
 *
 * @method static $this OPEN()
 * @method static $this STOP()
 * @method static $this INCLUDES()
 */
class DiscountCombine extends EnumSingleton implements EnumTranslatableInterface
{
    use EnumTranslatableTrait;

    public const OPEN = 'open';
    public const STOP = 'stop';
    public const INCLUDES = 'includes';

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

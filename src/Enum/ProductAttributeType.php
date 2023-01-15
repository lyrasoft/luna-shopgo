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
use Windwalker\Utilities\Attributes\Enum\Color;
use Windwalker\Utilities\Attributes\Enum\Icon;
use Windwalker\Utilities\Enum\EnumSingleton;
use Windwalker\Utilities\Enum\EnumTranslatableInterface;
use Windwalker\Utilities\Enum\EnumTranslatableTrait;
use Windwalker\Utilities\Contract\LanguageInterface;

/**
 * The ProductAttributeType enum class.
 *
 * @method static $this TEXT()
 * @method static $this SELECT()
 * @method static $this BOOL()
 */
class ProductAttributeType extends EnumSingleton implements EnumTranslatableInterface
{
    use EnumTranslatableTrait;

    #[Color('secondary')]
    #[Icon('font')]
    public const TEXT = 'text';

    #[Color('primary')]
    #[Icon('list')]
    public const SELECT = 'select';

    #[Color('danger')]
    #[Icon('toggle-on')]
    public const BOOL = 'bool';

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
        return $lang->trans('shopgo.product.attribute.type.' . $this->getKey());
    }
}

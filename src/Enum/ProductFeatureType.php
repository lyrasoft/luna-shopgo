<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Enum;

use Windwalker\Utilities\Attributes\Enum\Color;
use Windwalker\Utilities\Attributes\Enum\Icon;
use Windwalker\Utilities\Contract\LanguageInterface;
use Windwalker\Utilities\Enum\EnumSingleton;
use Windwalker\Utilities\Enum\EnumTranslatableInterface;
use Windwalker\Utilities\Enum\EnumTranslatableTrait;

/**
 * The FeatureType enum class.
 *
 * @method static $this SELECT()
 * @method static $this COLOR()
 */
class ProductFeatureType extends EnumSingleton implements EnumTranslatableInterface
{
    use EnumTranslatableTrait;

    #[Color('primary')]
    #[Icon('list')]
    public const SELECT = 'select';

    #[Color('danger')]
    #[Icon('palette')]
    public const COLOR = 'color';

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
        return $lang->trans('shopgo.product.feature.type.' . $this->getKey());
    }
}

<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Enum;

use App\Enum\T;
use Windwalker\Utilities\Contract\LanguageInterface;
use Windwalker\Utilities\Enum\EnumSingleton;
use Windwalker\Utilities\Enum\EnumTranslatableInterface;
use Windwalker\Utilities\Enum\EnumTranslatableTrait;

/**
 * The LocationType enum class.
 *
 * @method static $this ROOT()
 * @method static $this CONTINENT()
 * @method static $this COUNTRY()
 * @method static $this STATE()
 * @method static $this CITY()
 */
class LocationType extends EnumSingleton implements EnumTranslatableInterface
{
    use EnumTranslatableTrait;

    public const ROOT = 'root';

    public const CONTINENT = 'continent';

    public const COUNTRY = 'country';

    public const STATE = 'state';

    public const CITY = 'city';

    /**
     * Creates a new value of some type
     *
     * @psalm-pure
     *
     * @param  mixed  $value
     *
     * @psalm-param T $value
     * @throws \UnexpectedValueException if incompatible type is given.
     */
    protected function __construct(mixed $value)
    {
        parent::__construct($value);
    }

    public function trans(LanguageInterface $lang, ...$args): string
    {
        return $lang->trans('shopgo.location.type.' . $this->getKey());
    }

    /**
     * @return  array<static>
     */
    public static function nonRootTypes(): array
    {
        return [
            static::CONTINENT(),
            static::COUNTRY(),
            static::STATE(),
            static::CITY(),
        ];
    }
}

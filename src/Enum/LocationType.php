<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Enum;

use App\Enum\T;
use Windwalker\Utilities\Contract\LanguageInterface;
use Windwalker\Utilities\Enum\EnumTranslatableInterface;
use Windwalker\Utilities\Enum\EnumTranslatableTrait;

enum LocationType: string implements EnumTranslatableInterface
{
    use EnumTranslatableTrait;

    case ROOT = 'root';
    case CONTINENT = 'continent';
    case COUNTRY = 'country';
    case STATE = 'state';
    case CITY = 'city';

    public function trans(LanguageInterface $lang, ...$args): string
    {
        return $lang->trans('shopgo.location.type.' . $this->getKey());
    }

    public static function nonRootTypes(): array
    {
        return [
            static::CONTINENT,
            static::COUNTRY,
            static::STATE,
            static::CITY,
        ];
    }
}

<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Enum;

use Windwalker\Utilities\Attributes\Enum\Color;
use Windwalker\Utilities\Attributes\Enum\Icon;
use Windwalker\Utilities\Contract\LanguageInterface;
use Windwalker\Utilities\Enum\EnumTranslatableInterface;
use Windwalker\Utilities\Enum\EnumTranslatableTrait;

enum ProductFeatureType: string implements EnumTranslatableInterface
{
    use EnumTranslatableTrait;

    #[Color('primary')]
    #[Icon('list')]
    case SELECT = 'select';
    #[Color('danger')]
    #[Icon('palette')]
    case COLOR = 'color';

    public function trans(LanguageInterface $lang, ...$args): string
    {
        return $lang->trans('shopgo.product.feature.type.' . $this->name);
    }
}

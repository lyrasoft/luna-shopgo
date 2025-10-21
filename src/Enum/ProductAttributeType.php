<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Enum;

use Windwalker\Utilities\Attributes\Enum\Color;
use Windwalker\Utilities\Attributes\Enum\Icon;
use Windwalker\Utilities\Contract\LanguageInterface;
use Windwalker\Utilities\Enum\EnumTranslatableInterface;
use Windwalker\Utilities\Enum\EnumTranslatableTrait;

enum ProductAttributeType: string implements EnumTranslatableInterface
{
    use EnumTranslatableTrait;

    #[Color('secondary')]
    #[Icon('font')]
    case TEXT = 'text';
    #[Color('primary')]
    #[Icon('list')]
    case SELECT = 'select';
    #[Color('danger')]
    #[Icon('toggle-on')]
    case BOOL = 'bool';

    public function trans(LanguageInterface $lang, ...$args): string
    {
        return $lang->trans('shopgo.product.attribute.type.' . $this->name);
    }
}

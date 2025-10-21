<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Enum;

use Windwalker\Utilities\Contract\LanguageInterface;
use Windwalker\Utilities\Enum\EnumTranslatableInterface;
use Windwalker\Utilities\Enum\EnumTranslatableTrait;

enum SignPosition: string implements EnumTranslatableInterface
{
    use EnumTranslatableTrait;

    case START = 'start';
    case END = 'end';

    public function trans(LanguageInterface $lang, ...$args): string
    {
        return $lang->trans('shopgo.currency.sign.position.' . $this->name);
    }
}

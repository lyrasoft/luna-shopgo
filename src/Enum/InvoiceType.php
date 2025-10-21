<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Enum;

use Windwalker\Utilities\Contract\LanguageInterface;
use Windwalker\Utilities\Enum\EnumTranslatableInterface;
use Windwalker\Utilities\Enum\EnumTranslatableTrait;

enum InvoiceType: string implements EnumTranslatableInterface
{
    use EnumTranslatableTrait;

    case IDV = 'idv';
    case COMPANY = 'company';

    public static function preprocessValue(mixed $value): mixed
    {
        return $value ?: self::IDV;
    }

    public function trans(LanguageInterface $lang, ...$args): string
    {
        return $lang->trans('shopgo.invoice.type.' . $this->getKey());
    }
}

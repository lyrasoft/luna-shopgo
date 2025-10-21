<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Enum;

use Windwalker\Utilities\Contract\LanguageInterface;
use Windwalker\Utilities\Enum\EnumTranslatableInterface;
use Windwalker\Utilities\Enum\EnumTranslatableTrait;

enum OrderNoMode: string implements EnumTranslatableInterface
{
    use EnumTranslatableTrait;

    case INCREMENT_ID = 'increment_id';
    case DAILY_SEQUENCE = 'daily_sequence';
    case SEQUENCE_HASHES = 'sequence_hashes';
    case RANDOM_HASHES = 'random_hashes';

    public function trans(LanguageInterface $lang, ...$args): string
    {
        return $lang->trans('shopgo.order.no.mode.' . $this->getKey());
    }
}

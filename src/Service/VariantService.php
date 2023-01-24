<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Service;

use App\Data\ListOption;
use App\Data\ListOptionCollection;

/**
 * The VariantService class.
 */
class VariantService
{
    /**
     * @param  array<ListOption|array>  $options
     *
     * @return  string
     */
    public static function hashByOptions(array|ListOptionCollection $options): string
    {
        $values = ListOptionCollection::wrap($options)
            ->map(static fn ($option) => $option['value'])
            ->dump();

        return static::hash($values);
    }

    public static function hash(array $values): string
    {
        sort($values);

        return md5(implode(':', $values));
    }
}

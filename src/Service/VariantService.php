<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Service;

use Lyrasoft\ShopGo\Data\ListOption;
use Lyrasoft\ShopGo\Data\ListOptionCollection;

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

    /**
     * @param  array  $featureOptGroups
     * @param  array  $parentGroup
     *
     * @return  array<array<array{ text: string, value: string }>>
     */
    public function sortOptionsGroup(array $featureOptGroups, array $parentGroup = []): array
    {
        $currentOptions = array_pop($featureOptGroups);

        $returnValue = [];

        foreach ($currentOptions as $option) {
            $group = $parentGroup;

            $group[] = $option;

            if (\count($featureOptGroups)) {
                $returnValue[] = $this->sortOptionsGroup($featureOptGroups, $group);
            } else {
                $returnValue[] = [$group];
            }
        }

        return array_merge(...$returnValue);
    }
}

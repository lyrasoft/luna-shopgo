<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Data;

use Windwalker\Data\Collection;

/**
 * The ListOptionCollection class.
 */
class ShippingHistoryCollection extends Collection
{
    public function fill(mixed $data, array $options = []): static
    {
        $data = array_map(
            static fn ($item) => ShippingHistory::wrap($item),
            $data
        );

        return parent::fill($data, $options);
    }
}

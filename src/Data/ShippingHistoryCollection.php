<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Data;

use Windwalker\Data\Collection;
use Windwalker\Data\ValueObject;

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

<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Data;

use Windwalker\Data\Collection;
use Windwalker\Utilities\TypeCast;

/**
 * The ListOptionCollection class.
 */
class ListOptionCollection extends Collection
{
    /**
     * ArrayObject constructor.
     *
     * @param  array  $storage
     */
    public function __construct($storage = [])
    {
        $this->fill($storage);
    }

    public function fill(mixed $data, array $options = []): static
    {
        $data = array_map(
            static fn ($item) => ListOption::wrap($item),
            TypeCast::toArray($data)
        );

        return parent::fill($data, $options);
    }
}

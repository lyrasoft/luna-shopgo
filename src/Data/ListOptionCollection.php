<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Data;

use Windwalker\Data\Collection;
use Windwalker\Utilities\TypeCast;

/**
 * The ListOptionCollection class.
 *
 * @template-extends \IteratorAggregate<int, ListOption>
 */
#[\AllowDynamicProperties]
class ListOptionCollection extends Collection
{
    public function fill(mixed $data, array $options = []): static
    {
        $data = array_map(
            static fn ($item) => ListOption::wrap($item),
            TypeCast::toArray($data)
        );

        return parent::fill($data, $options);
    }
}

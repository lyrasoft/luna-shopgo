<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Field;

use Lyrasoft\ShopGo\Entity\OrderState;
use Unicorn\Field\SqlListField;
use Windwalker\Query\Query;

/**
 * The OrderStateListField class.
 */
class OrderStateListField extends SqlListField
{
    protected ?string $table = OrderState::class;

    protected function prepareQuery(Query $query): void
    {
        parent::prepareQuery($query);

        $query->order('ordering', 'ASC');
    }

    /**
     * getAccessors
     *
     * @return  array
     */
    protected function getAccessors(): array
    {
        return array_merge(
            parent::getAccessors(),
            []
        );
    }
}

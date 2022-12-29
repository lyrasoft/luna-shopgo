<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Field;

use App\Entity\OrderState;
use Unicorn\Field\SqlListField;

/**
 * The OrderStateListField class.
 */
class OrderStateListField extends SqlListField
{
    protected ?string $table = OrderState::class;

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

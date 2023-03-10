<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Field;

use Lyrasoft\ShopGo\Entity\Location;
use Unicorn\Field\ModalField;

/**
 * The LocationModalField class.
 */
class LocationModalField extends ModalField
{
    protected ?string $table = Location::class;

    protected function configure(): void
    {
        $this->route('location_list');
        $this->table(Location::class);
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

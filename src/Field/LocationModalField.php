<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Field;

use App\Entity\Location;
use Unicorn\Field\ModalField;
use Windwalker\DOM\DOMElement;

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
     * prepareInput
     *
     * @param  DOMElement  $input
     *
     * @return  DOMElement
     */
    public function prepareInput(DOMElement $input): DOMElement
    {
        return $input;
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

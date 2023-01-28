<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Field;

use App\Entity\Currency;
use Unicorn\Field\SqlListField;
use Windwalker\DOM\DOMElement;
use Windwalker\Form\Field\AbstractField;

/**
 * The CurrencyListField class.
 */
class CurrencyListField extends SqlListField
{
    protected ?string $table = Currency::class;

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

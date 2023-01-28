<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Field;

use App\Entity\Discount;
use Unicorn\Field\ModalField;
use Windwalker\DOM\DOMElement;
use Windwalker\Form\Field\AbstractField;

/**
 * The DiscountModalField class.
 */
class DiscountModalField extends ModalField
{
    protected function configure(): void
    {
        $this->route('discount_list');
        $this->table(Discount::class);
    }

    protected function getItemTitle(): ?string
    {
        return $this->getItem()['title'] ?? '';
    }
}

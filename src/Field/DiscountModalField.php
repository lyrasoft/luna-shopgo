<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Field;

use Lyrasoft\ShopGo\Entity\Discount;
use Unicorn\Field\ModalField;

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

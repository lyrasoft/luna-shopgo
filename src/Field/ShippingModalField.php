<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Field;

use Lyrasoft\ShopGo\Entity\Shipping;
use Unicorn\Field\ModalField;

/**
 * The ShippingModalField class.
 */
class ShippingModalField extends ModalField
{
    protected ?string $table = Shipping::class;

    protected function configure(): void
    {
        $this->route('shipping_list');
        $this->table(Shipping::class);
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

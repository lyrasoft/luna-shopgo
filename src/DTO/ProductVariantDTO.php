<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyraoft\ShopGo\DTO;

use Windwalker\Data\AbstractDTO;

/**
 * The ProductVariantDTO class.
 */
class ProductVariantDTO extends AbstractDTO
{
    protected function configure(object $data): void
    {
        $this->addKeepFields(
            'id',
            'productId',
            'title',
            'cover',
            'applyDiscounts',
            'priceSet',
            'category',
            'product',
            'primary',
        );
    }
}

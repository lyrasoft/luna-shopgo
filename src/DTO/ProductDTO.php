<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\DTO;

use Windwalker\Data\AbstractDTO;

/**
 * The ProductDTO class.
 */
class ProductDTO extends AbstractDTO
{
    protected function configure(object $data): void
    {
        $this->addKeepFields(
            'id',
            'model',
            'title',
            'originPrice',
            'safeStock',
            'variants',
            'state',
        );
    }
}

<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Cart\Contract;

use Windwalker\Core\Application\AppContext;

/**
 * Interface CheckoutProcessLayoutInterface
 */
interface CheckoutProcessLayoutInterface
{
    public function renderProcessLayout(AppContext $app): mixed;
}

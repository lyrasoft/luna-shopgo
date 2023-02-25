<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Cart\Contract;

use Lyrasoft\ShopGo\Cart\CartData;

/**
 * Interface CheckoutProcessLayoutInterface
 */
interface CheckoutProcessLayoutInterface
{
    public function checkoutLayout(CartData $cartData): mixed;
}

<?php

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

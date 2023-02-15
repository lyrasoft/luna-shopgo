<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Event;

use Lyrasoft\ShopGo\Cart\CartItem;
use Lyrasoft\ShopGo\Data\Contract\ProductPricingInterface;
use Lyrasoft\ShopGo\Data\Traits\CartTotalsTrait;
use Lyrasoft\ShopGo\Data\Traits\ProductPricingTrait;
use Windwalker\Event\AbstractEvent;

/**
 * The PrepareProductTotalsEvent class.
 */
class PrepareProductPricesEvent extends AbstractEvent implements ProductPricingInterface
{
    use ProductPricingTrait;
    use CartTotalsTrait;

    public const PRODUCT_VIEW = 'product_view';

    public const CART = 'cart';

    public const ORDER = 'order';

    public string $context = self::PRODUCT_VIEW;

    public CartItem $cartItem;

    /**
     * @return string
     */
    public function getContext(): string
    {
        return $this->context;
    }

    /**
     * @param  string  $context
     *
     * @return  static  Return self to support chaining.
     */
    public function setContext(string $context): static
    {
        $this->context = $context;

        return $this;
    }

    /**
     * @return CartItem
     */
    public function getCartItem(): CartItem
    {
        return $this->cartItem;
    }

    /**
     * @param  CartItem  $cartItem
     *
     * @return  static  Return self to support chaining.
     */
    public function setCartItem(CartItem $cartItem): static
    {
        $this->cartItem = $cartItem;

        return $this;
    }
}

<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Data\Contract;

use Lyrasoft\ShopGo\Cart\CartData;
use Lyrasoft\ShopGo\Cart\CartItem;
use Lyrasoft\ShopGo\Cart\Price\PriceObject;
use Lyrasoft\ShopGo\Cart\Price\PriceSet;
use Lyrasoft\ShopGo\Entity\Discount;
use Lyrasoft\ShopGo\Entity\Product;

/**
 * Interface CartTotalsInterface
 */
interface CartTotalsInterface extends DiscountsAppliedInterface
{
    public CartData $cartData { get; set; }

    public PriceObject $total { get; set; }

    public PriceSet $totals { get; set; }

    public \SplObjectStorage $matchedItems { get; set; }

    /**
     * @param  Discount  $discount
     * @param  CartItem  $cartItem
     *
     * @return  static  Return self to support chaining.
     */
    public function addMatchedItem(Discount $discount, CartItem $cartItem): static;
}

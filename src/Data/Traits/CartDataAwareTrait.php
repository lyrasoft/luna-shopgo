<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Data\Traits;

use Lyrasoft\ShopGo\Cart\CartData;
use Lyrasoft\ShopGo\Cart\CartItem;
use Lyrasoft\ShopGo\Cart\Price\PriceObject;
use Lyrasoft\ShopGo\Cart\Price\PriceSet;
use Lyrasoft\ShopGo\Entity\Discount;
use Lyrasoft\ShopGo\Entity\Product;

/**
 * Trait OrderTotalsTrait
 */
trait CartDataAwareTrait
{
    use DiscountsAppliedTrait;

    public CartData $cartData {
        get {
            $this->cartData->totals = $this->totals;

            return $this->cartData;
        }
        set => $this->cartData = $value;
    }

    public PriceObject $total;

    public PriceSet $totals;

    public \SplObjectStorage $matchedItems {
        get => $this->matchedItems ??= new \SplObjectStorage();
        set => $this->matchedItems = $value;
    }

    /**
     * @param  Discount  $discount
     * @param  CartItem  $cartItem
     *
     * @return  static  Return self to support chaining.
     */
    public function addMatchedItem(Discount $discount, CartItem $cartItem): static
    {
        $items = $this->matchedItems;

        if (!isset($items[$discount])) {
            $items[$discount] = [];
        }

        $storage = $items[$discount];

        $storage[$cartItem->uid] = $cartItem;

        $items[$discount] = $storage;

        $this->matchedItems = $items;

        return $this;
    }
}

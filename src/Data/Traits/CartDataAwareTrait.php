<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

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

    public CartData $cartData;

    public PriceObject $total;

    public PriceSet $totals;

    public ?\SplObjectStorage $matchedItems = null;

    /**
     * @return CartData
     */
    public function getCartData(): CartData
    {
        $this->cartData->setTotals($this->getTotals());

        return $this->cartData;
    }

    /**
     * @param  CartData  $cartData
     *
     * @return  static  Return self to support chaining.
     */
    public function setCartData(CartData $cartData): static
    {
        $this->cartData = $cartData;

        return $this;
    }

    /**
     * @return PriceSet
     */
    public function getTotals(): PriceSet
    {
        return $this->totals;
    }

    /**
     * @param  PriceSet  $totals
     *
     * @return  static  Return self to support chaining.
     */
    public function setTotals(PriceSet $totals): static
    {
        $this->totals = $totals;

        return $this;
    }

    /**
     * @return PriceObject
     */
    public function getTotal(): PriceObject
    {
        return $this->total;
    }

    /**
     * @param  PriceObject  $total
     *
     * @return  static  Return self to support chaining.
     */
    public function setTotal(PriceObject $total): static
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return \SplObjectStorage<Discount, CartItem>
     */
    public function getMatchedItems(): \SplObjectStorage
    {
        return $this->matchedItems ??= new \SplObjectStorage();
    }

    /**
     * @param  \SplObjectStorage<Discount, CartItem>  $matchedItems
     *
     * @return  static  Return self to support chaining.
     */
    public function setMatchedItems(\SplObjectStorage $matchedItems): static
    {
        $this->matchedItems = $matchedItems;

        return $this;
    }

    /**
     * @param  Discount  $discount
     * @param  CartItem  $cartItem
     *
     * @return  static  Return self to support chaining.
     */
    public function addMatchedItem(Discount $discount, CartItem $cartItem): static
    {
        $items = $this->getMatchedItems();

        if (!isset($items[$discount])) {
            $items[$discount] = [];
        }

        $storage = $items[$discount];

        $storage[$cartItem->getUid()] = $cartItem;

        $items[$discount] = $storage;

        $this->setMatchedItems($items);

        return $this;
    }
}

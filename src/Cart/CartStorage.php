<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Cart;

use Windwalker\Core\State\AppState;

/**
 * The CartStorage class.
 *
 * @psalm-type CartStorageItem = array{ variantId: int, isAdditionalOf: ?int, quantity: int }
 */
class CartStorage
{
    protected const KEY_NAME = 'cart.items';

    public function __construct(protected AppState $state)
    {
    }

    public function addToCart(int $variantId, int $quantity = 1, ?int $isAdditionalOf = null): void
    {
        $items = $this->getStoredItems();

        $key = $this->getKeyName($variantId, $isAdditionalOf);

        if (isset($items[$key])) {
            $items[$key]['quantity'] += $quantity;
        } else {
            $items[$key] = compact('variantId', 'quantity', 'isAdditionalOf');
        }

        $this->setStoredItems($items);
    }

    public function removeFromCart(mixed $id): void
    {
        $items = $this->getStoredItems();

        unset($items[$id]);

        $this->setStoredItems($items);
    }

    public function changeItemQuantity(
        int $variantId,
        ?int $isAdditionalOf = null,
        int $offsets = 1
    ): void {
        $items = $this->getStoredItems();

        $key = $this->getKeyName($variantId, $isAdditionalOf);

        if (!isset($items[$key])) {
            throw new \RuntimeException("Item: $variantId not found in cart");
        }

        $items[$key]['quantity'] += $offsets;

        $this->setStoredItems($items);
    }

    public function setItemQuantity(int $variantId, ?int $isAdditionalOf = null, int $quantity = 1): void
    {
        $items = $this->getStoredItems();

        $key = $this->getKeyName($variantId, $isAdditionalOf);

        if (!isset($items[$key])) {
            throw new \RuntimeException("Item: $variantId not found in cart");
        }

        $items[$key]['quantity'] = $quantity;

        $this->setStoredItems($items);
    }

    public function clear(): void
    {
        $this->state->remember(static::KEY_NAME, []);
    }

    /**
     * getStoredItems
     *
     * @return  array<CartStorageItem>
     */
    public function getStoredItems(): array
    {
        return (array) $this->state->get(static::KEY_NAME);
    }

    /**
     * @param  array<CartStorageItem>  $items
     *
     * @return  void
     */
    public function setStoredItems(array $items): void
    {
        $this->state->remember(static::KEY_NAME, $items);
    }

    public function count(): int
    {
        return count($this->getStoredItems());
    }

    /**
     * @param  int       $variantId
     * @param  int|null  $isAdditionalOf
     *
     * @return  string
     */
    protected function getKeyName(int $variantId, ?int $isAdditionalOf = null): string
    {
        return $variantId . ':' . ($isAdditionalOf ?? 0);
    }
}

<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Cart;

use Lyrasoft\ShopGo\Entity\AdditionalPurchaseMap;
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

    public function addToCart(int $variantId, int $quantity = 1, array $payload = []): void
    {
        $items = $this->getStoredItems();

        $key = $this->getKeyName($variantId, $payload);

        if (isset($items[$key])) {
            $items[$key]['quantity'] += $quantity;
        } else {
            $items[$key] = array_merge(
                compact('variantId', 'quantity', 'key'),
                $payload
            );
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
        int $offsets = 1,
        array $payload = []
    ): void {
        $items = $this->getStoredItems();

        $key = $this->getKeyName($variantId, $payload);

        if (!isset($items[$key])) {
            throw new \RuntimeException("Item: $variantId not found in cart");
        }

        $items[$key]['quantity'] += $offsets;

        $this->setStoredItems($items);
    }

    public function setItemQuantity(int $variantId, int $quantity = 1, array $payload = []): void
    {
        $items = $this->getStoredItems();

        $key = $this->getKeyName($variantId, $payload);

        if (!isset($items[$key])) {
            throw new \RuntimeException("Item: $variantId not found in cart");
        }

        $items[$key]['quantity'] = $quantity;

        $this->setStoredItems($items);
    }

    public function updateQuantities(array $values): void
    {
        $items = $this->getStoredItems();

        foreach ($values as $key => $quantity) {
            if (!isset($items[$key])) {
                continue;
            }

            $items[$key]['quantity'] = (int) $quantity;
        }

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
     * @param  int    $variantId
     * @param  array  $payload
     *
     * @return  string
     */
    protected function getKeyName(int $variantId, array $payload = []): string
    {
        sort($payload);

        $payload = array_map(static fn ($v) => $v ?: '0', $payload);

        array_unshift($payload, $variantId);

        return implode(':', $payload);
    }

    public function addAdditional(AdditionalPurchaseMap $apMap): void
    {
        $variantId = $apMap->getAttachVariantId();

        $isAdditionalOf = $apMap->getTargetProductId();
        $apMapId = $apMap->getId();

        $this->addToCart($variantId, 1, compact('isAdditionalOf', 'apMapId'));
    }
}

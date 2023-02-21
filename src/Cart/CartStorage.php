<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Cart;

use Lyrasoft\ShopGo\Entity\AdditionalPurchaseTarget;
use Windwalker\Core\State\AppState;
use Windwalker\Utilities\Arr;

/**
 * The CartStorage class.
 *
 * @psalm-type CartStorageItem = array{ variantId: int, attachments: ?array<int, int>, quantity: int }
 */
class CartStorage
{
    protected const CART_ITEMS_KEY = 'cart.items';
    protected const COUPONS_KEY = 'cart.coupons';

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

    public function remove(int $variantId, array $payload = []): void
    {
        $key = $this->getKeyName($variantId, $payload);

        $this->removeByKey($key);
    }

    public function removeByKey(mixed $key): void
    {
        $items = $this->getStoredItems();

        unset($items[$key]);

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
        $this->state->remember(static::CART_ITEMS_KEY, []);
    }

    /**
     * getStoredItems
     *
     * @return  array<CartStorageItem>
     */
    public function getStoredItems(): array
    {
        return (array) $this->state->get(static::CART_ITEMS_KEY);
    }

    /**
     * @param  array<CartStorageItem>  $items
     *
     * @return  void
     */
    public function setStoredItems(array $items): void
    {
        $this->state->remember(static::CART_ITEMS_KEY, $items);
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
     * @throws \JsonException
     */
    protected function getKeyName(int $variantId, array $payload = []): string
    {
        if ($payload === []) {
            return (string) $variantId;
        }

        $key = (string) $variantId;

        // Will build key like this: `1536` or `1536:attachments:25=3,27=2`
        foreach ($payload as $k => $values) {
            if (is_scalar($values)) {
                if ($values === '') {
                    continue;
                }

                $key .= '|' . $k . ':' . $values;
            }

            if (is_array($values)) {
                if ($values === []) {
                    continue;
                }

                ksort($values);

                foreach ($values as $k2 => &$value) {
                    $value = "$k2=$value";
                }

                unset($value);

                $key .= '|' . $k . ':' . implode(',', $values);
            }
        }

        return $key;
    }

    /**
     * @param  int  $id
     *
     * @return  static
     *
     * @since  __DEPLOY_VERSION__
     */
    public function addCoupon(int $id): self
    {
        $key = static::COUPONS_KEY;

        $coupons = (array) $this->state->get(static::COUPONS_KEY);

        $coupons[] = $id;
        $coupons = array_unique($coupons);

        $this->state->remember($key, $coupons);

        return $this;
    }

    /**
     * @param  int  $id
     *
     * @return CartStorage
     */
    public function removeCoupon(int $id): static
    {
        $coupons = $this->getCoupons();

        $coupons = array_filter($coupons, static fn($couponId) => (int) $couponId !== $id);

        $this->state->remember(static::COUPONS_KEY, $coupons);

        return $this;
    }

    /**
     * @return  array
     *
     * @since  __DEPLOY_VERSION__
     */
    public function getCoupons(): array
    {
        $key = static::COUPONS_KEY;

        return array_unique((array) ($this->state->get($key) ?? []));
    }

    /**
     * @return  static
     *
     * @since  __DEPLOY_VERSION__
     */
    public function clearCoupons(): self
    {
        $key = static::COUPONS_KEY;

        $this->state->forget($key);

        return $this;
    }
}

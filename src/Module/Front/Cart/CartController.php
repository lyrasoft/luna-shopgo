<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Module\Front\Cart;

use Lyrasoft\ShopGo\Cart\CartService;
use Lyrasoft\ShopGo\Cart\CartStorage;
use Lyrasoft\ShopGo\Entity\Product;
use Lyrasoft\ShopGo\Entity\ProductVariant;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Attributes\Controller;
use Windwalker\ORM\ORM;

/**
 * The CartController class.
 */
#[Controller]
class CartController
{
    public function ajax(AppContext $app): mixed
    {
        $task = $app->input('task');

        return $app->call([$this, $task]);
    }

    public function addToCart(AppContext $app, CartStorage $cartStorage): array
    {
        [
            $id,
            $hash,
            $isAdditionalOf,
            $quantity,
        ] = $app->input('id', 'hash', 'isAdditionalOf', 'quantity')->values();

        /** @var ProductVariant $variant */
        $variant = $app->call(
            [$this, 'checkProductExists'],
            [
                'id' => (int) $id,
                'hash' => (string) $hash,
                'isAdditionalOf' => $isAdditionalOf
            ]
        );

        $cartStorage->addToCart($variant->getId(), (string) $hash, $isAdditionalOf, (int) $quantity);

        return $cartStorage->getStoredItems();
    }

    public function getItems(AppContext $app, CartService $cartService)
    {
        $cartService->getCartData();
    }

    protected function checkProductExists(int $id, string $hash, ?int $isAdditionalOf, ORM $orm): ProductVariant
    {
        $orm->mustFindOne(Product::class, $id);
        $variant = $orm->mustFindOne(ProductVariant::class, ['product_id' => $id, 'hash' => $hash]);

        if ($isAdditionalOf) {
            $orm->mustFindOne(Product::class, $isAdditionalOf);
        }

        return $variant;
    }
}

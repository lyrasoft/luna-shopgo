<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Module\Front\Cart;

use Lyrasoft\ShopGo\Cart\CartData;
use Lyrasoft\ShopGo\Cart\CartService;
use Lyrasoft\ShopGo\Cart\CartStorage;
use Lyrasoft\ShopGo\Entity\AdditionalPurchase;
use Lyrasoft\ShopGo\Entity\AdditionalPurchaseTarget;
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
            $variantId,
            $quantity,
        ] = $app->input('product_id', 'variant_id', 'quantity')->values();

        /** @var ProductVariant $variant */
        $variant = $app->call(
            [$this, 'validateProductVariant'],
            [
                'id' => (int) $id,
                'variantId' => (int) $variantId
            ]
        );

        $cartStorage->addToCart($variant->getId(), (int) $quantity);

        return array_values($cartStorage->getStoredItems());
    }

    public function addon(AppContext $app, CartStorage $cartStorage): array
    {
        $apMapId = (int) $app->input('apMapId');

        /** @var AdditionalPurchaseTarget $apMap */
        $apMap = $app->call(
            [$this, 'validateAdditionalPurchase'],
            [
                'apMapId' => $apMapId
            ]
        );

        $cartStorage->addAdditional($apMap);

        return array_values($cartStorage->getStoredItems());
    }

    public function updateQuantities(AppContext $app, CartStorage $cartStorage, CartService $cartService): CartData
    {
        $values = (array) $app->input('values');

        $cartStorage->updateQuantities($values);

        return $cartService->getCartData();
    }

    public function getItems(AppContext $app, CartService $cartService): CartData
    {
        return $cartService->getCartData();
    }

    public function validateProductVariant(int $id, int $variantId, ORM $orm): ProductVariant
    {
        $orm->mustFindOne(Product::class, $id);

        return $orm->mustFindOne(ProductVariant::class, ['product_id' => $id, 'id' => $variantId]);
    }

    public function validateAdditionalPurchase(int $apMapId, ORM $orm, CartStorage $cartStorage): AdditionalPurchaseTarget
    {
        $map = $orm->mustFindOne(AdditionalPurchaseTarget::class, $apMapId);
        $ap = $orm->mustFindOne(AdditionalPurchase::class, $map->getAdditionalPurchaseId());

        $orm->mustFindOne(Product::class, $map->getTargetProductId());
        $orm->mustFindOne(Product::class, $ap->getAttachProductId());
        $orm->mustFindOne(ProductVariant::class, $ap->getAttachVariantId());

        $variantIds = $orm->findColumn(
            ProductVariant::class,
            'id',
            ['product_id' => $map->getTargetProductId()]
        )
            ->map('intval')
            ->dump();

        $items = $cartStorage->getStoredItems();
        $exists = false;

        foreach ($items as $item) {
            if (isset($item['isAdditionalOf'])) {
                continue;
            }

            $exists = $exists || in_array((int) $item['variantId'], $variantIds, true);
        }

        if (!$exists) {
            throw new \RuntimeException('請先加入主要商品才能加購');
        }

        return $map;
    }
}

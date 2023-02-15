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
use Lyrasoft\ShopGo\Entity\AdditionalPurchaseAttachment;
use Lyrasoft\ShopGo\Entity\AdditionalPurchaseTarget;
use Lyrasoft\ShopGo\Entity\Product;
use Lyrasoft\ShopGo\Entity\ProductVariant;
use Lyrasoft\ShopGo\Service\AdditionalPurchaseService;
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
            $attachments,
        ] = $app->input('product_id', 'variant_id', 'quantity', 'attachments')->values();

        /** @var ProductVariant $variant */
        [$product, $variant] = $app->call(
            [$this, 'validateProductVariant'],
            [
                'id' => (int) $id,
                'variantId' => (int) $variantId,
            ]
        );

        $attachments = (array) $attachments;

        if ($attachments !== []) {
            $app->call(
                [$this, 'validateAdditionalPurchase'],
                [
                    'targetProduct' => $product,
                    'attachments' => $attachments
                ]
            );
        }

        $cartStorage->addToCart($variant->getId(), (int) $quantity, compact('attachments'));

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

    /**
     * @param  int  $id
     * @param  int  $variantId
     * @param  ORM  $orm
     *
     * @return  array{ 0: Product, 1: ProductVariant }
     */
    public function validateProductVariant(int $id, int $variantId, ORM $orm): array
    {
        $product = $orm->mustFindOne(Product::class, $id);

        $variant = $orm->mustFindOne(ProductVariant::class, ['product_id' => $id, 'id' => $variantId]);

        return [$product, $variant];
    }

    public function validateAdditionalPurchase(
        Product $targetProduct,
        array $attachments,
        ORM $orm,
        AdditionalPurchaseService $additionalPurchaseService
    ): void {
        $attachmentIds = array_keys($attachments);

        $attachItems = $orm->findList(AdditionalPurchaseAttachment::class, ['id' => $attachmentIds])->all();

        foreach ($attachItems as $attachItem) {
            $additionalPurchaseService->validateAttachment($attachItem, $targetProduct);
        }
    }
}

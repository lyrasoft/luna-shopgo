<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Module\Front\Cart;

use Lyrasoft\Luna\User\UserService;
use Lyrasoft\ShopGo\Cart\CartData;
use Lyrasoft\ShopGo\Cart\CartService;
use Lyrasoft\ShopGo\Cart\CartStorage;
use Lyrasoft\ShopGo\Data\CartPricingData;
use Lyrasoft\ShopGo\Entity\AdditionalPurchaseAttachment;
use Lyrasoft\ShopGo\Entity\Location;
use Lyrasoft\ShopGo\Entity\Product;
use Lyrasoft\ShopGo\Entity\ProductVariant;
use Lyrasoft\ShopGo\Entity\Shipping;
use Lyrasoft\ShopGo\Payment\PaymentService;
use Lyrasoft\ShopGo\Service\AdditionalPurchaseService;
use Lyrasoft\ShopGo\Service\DiscountService;
use Lyrasoft\ShopGo\Shipping\ShippingService;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Attributes\Controller;
use Windwalker\Core\Renderer\RendererService;
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
                    'attachments' => $attachments,
                ]
            );
        }

        $cartStorage->addToCart($variant->getId(), (int) $quantity, compact('attachments'));

        return array_values($cartStorage->getStoredItems());
    }

    public function removeItem(AppContext $app, CartService $cartService, CartStorage $cartStorage): bool
    {
        $key = $app->input('key');

        $cartStorage->removeByKey($key);

        return true;
    }

    public function updateQuantities(AppContext $app, CartStorage $cartStorage, CartService $cartService): bool
    {
        $values = (array) $app->input('values');

        $cartStorage->updateQuantities($values);

        return true;
    }

    public function getItems(AppContext $app, CartService $cartService): CartData
    {
        $locationId = $app->input('location_id') ?? 0;
        $shippingId = $app->input('shipping_id') ?? 0;
        $paymentId = $app->input('payment_id') ?? 0;

        return $cartService->getCartData(
            [
                'location_id' => $locationId,
                'shipping_id' => $shippingId,
                'payment_id' => $paymentId,
            ]
        );
    }

    public function clearCart(AppContext $app, CartStorage $cartStorage): bool
    {
        $cartStorage->clear();
        $cartStorage->clearCoupons();

        return true;
    }

    public function addCode(
        AppContext $app,
        UserService $userService,
        CartService $cartService,
        CartStorage $cartStorage,
        DiscountService $discountService
    ): bool {
        $code = $app->input('code');

        if (!$code) {
            throw new \RuntimeException('沒有序號');
        }

        $cartData = $cartService->getCartData();

        if (!count($cartData->getItems())) {
            throw new \RuntimeException('購物車沒有商品', 403);
        }

        $user = $userService->getUser();

        $discounts = $discountService->findCodeDiscountsAndCoupons($code, $user->isLogin() ? $user : null);

        $data = new CartPricingData();
        $data->setTotals($cartData->getTotals())
            ->setTotal($cartData->getTotals()['total'])
            ->setCartData($cartData);

        $matched = null;

        foreach ($discounts as $discount) {
            if (!$discountService->matchDiscount($discount, $data)) {
                continue;
            }

            $matched = $discount;
            break;
        }

        if (!$matched) {
            throw new \RuntimeException('找不到合適的優惠券', 404);
        }

        $cartStorage->addCoupon($matched->getId());

        return true;
    }

    public function removeCode(AppContext $app, CartStorage $cartStorage): bool
    {
        $id = (int) $app->input('id');

        if (!$id) {
            return false;
        }

        $cartStorage->removeCoupon($id);

        return true;
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

    public function shippings(
        AppContext $app,
        ORM $orm,
        ShippingService $shippingService,
        CartService $cartService,
        RendererService $rendererService,
    ): iterable {
        $locationId = (int) $app->input('location_id');

        $location = $orm->findOne(Location::class, $locationId);

        if (!$location || !$location->isLeaf()) {
            return [];
        }

        $cartData = $cartService->getCartData(['location_id' => $location->getId()]);

        /** @var Product[] $products */
        $products = [];

        foreach ($cartData->getItems() as $item) {
            /** @var Product $product */
            $product = $item->getProduct()->getData();
            $products[$product->getId()] = $product;

            foreach ($item->getAttachments() as $attachment) {
                /** @var Product $product */
                $product = $attachment->getProduct()->getData();
                $products[$product->getId()] = $product;
            }
        }

        $shippings = $shippingService->getShippings($location, $products);

        foreach ($shippings as $shipping) {
            $instance = $shippingService->createTypeInstance($shipping);

            if (!$instance) {
                continue;
            }

            $fee = $app->call($instance->getShippingFeeComputer($cartData, $cartData->getTotals()['total']));

            $shipping->fee = $fee;

            $renderer = $rendererService->createRenderer();
            $shipping->optionLayout = $instance->renderOptionLayout(
                $renderer,
                compact('location')
            );
        }

        return $shippings;
    }

    public function payments(
        AppContext $app,
        ORM $orm,
        PaymentService $paymentService,
        RendererService $rendererService,
    ): iterable {
        $locationId = (int) $app->input('location_id');
        $shippingId = (int) $app->input('shipping_id');

        $location = $orm->findOne(Location::class, $locationId);

        if (!$location || !$location->isLeaf()) {
            return [];
        }

        $shipping = $orm->findOne(Shipping::class, $shippingId);

        if (!$shipping) {
            return [];
        }

        $payments = $paymentService->getPayments($location, $shipping);

        foreach ($payments as $payment) {
            $instance = $paymentService->createTypeInstance($payment);

            if (!$instance) {
                continue;
            }

            $renderer = $rendererService->createRenderer();
            $payment->optionLayout = $instance->renderOptionLayout(
                $renderer,
                compact('location')
            );
        }

        return $payments;
    }
}

<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Service;

use Lyrasoft\ShopGo\Cart\CartData;
use Lyrasoft\ShopGo\Cart\CartItem;
use Lyrasoft\ShopGo\Entity\Order;
use Lyrasoft\ShopGo\Entity\OrderItem;
use Lyrasoft\ShopGo\Entity\Product;
use Lyrasoft\ShopGo\Entity\ProductVariant;
use Windwalker\Core\Form\Exception\ValidateFailException;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\ORM\ORM;

/**
 * The StockService class.
 */
class StockService
{
    use TranslatorTrait;

    public function __construct(protected ORM $orm)
    {
    }

    public function checkAndReduceStocks(CartData $cartData): void
    {
        $this->checkStockOrReport($cartData);
        $this->reduceStocks($cartData);
    }

    /**
     * @param  CartData  $cartData
     *
     * @return  void
     */
    public function checkStockOrReport(CartData $cartData): void
    {
        foreach ($cartData->getCheckedItems() as $item) {
            if ($item->isOutOfStock()) {
                /** @var Product $product */
                $title = $this->getTitleFromCartItem($item);

                throw new ValidateFailException(
                    $this->trans('shopgo.message.product.out.of.stock', title: $title)
                );
            }

            foreach ($item->getAttachments() as $attachment) {
                $title = $this->getTitleFromCartItem($item);

                if ($attachment->isOutOfStock()) {
                    throw new ValidateFailException(
                        $this->trans('shopgo.message.product.out.of.stock', title: $title)
                    );
                }
            }
        }
    }

    /**
     * @param  CartItem  $item
     *
     * @return string
     */
    protected function getTitleFromCartItem(CartItem $item): string
    {
        /** @var Product $product */
        $product = $item->getProduct()->getData();
        /** @var ProductVariant $variant */
        $variant = $item->getVariant()->getData();

        $title = $product->getTitle();

        if (!$variant->isPrimary()) {
            $title .= ' - ' . $variant->getTitle();
        }

        return $title;
    }

    public function reduceStocks(CartData $cartData): void
    {
        $mapper = $this->orm->mapper(ProductVariant::class);

        $quantities = $cartData->getTotalQuantities(false, true);

        $variants = [];

        foreach ($cartData->getCheckedItems() as $item) {
            /** @var ProductVariant $variant */
            $variant = $item->getVariant()->getData();

            $quantity = $quantities[$variant->getId()] ?? 0;

            $variants[$variant->getId()] = [$variant, $quantity];

            foreach ($item->getAttachments() as $attachment) {
                /** @var ProductVariant $variant */
                $variant = $attachment->getVariant()->getData();

                $quantity = $quantities[$variant->getId()] ?? 0;

                $variants[$variant->getId()] = [$variant, $quantity];
            }
        }

        foreach ($variants as [$variant, $quantity]) {
            if ($quantity === 0) {
                continue;
            }

            $mapper->updateBatch(
                ['stock_quantity' => $variant->getStockQuantity() - $quantity],
                ['id' => $variant->getId()]
            );
        }
    }

    public function rollbackStocks(Order $order): void
    {
        $this->orm->getDb()->transaction(
            function () use ($order) {
                /** @var OrderItem[] $items */
                $items = $this->orm->from(OrderItem::class)
                    ->where('order_id', $order->getId())
                    ->all(OrderItem::class);

                $variantQuantities = [];

                foreach ($items as $item) {
                    $variantQuantities[$item->getVariantId()] ??= 0;

                    $variantQuantities[$item->getVariantId()] += $item->getQuantity();
                }

                /** @var OrderItem $item */
                foreach ($variantQuantities as $variantId => $quantity) {
                    /** @var ProductVariant $variant */
                    $variant = $this->orm->select()
                        ->from(ProductVariant::class)
                        ->where('id', $variantId)
                        ->forUpdate()
                        ->get(ProductVariant::class);

                    $variant->setStockQuantity(
                        $variant->getStockQuantity() + $quantity
                    );

                    $this->orm->updateOne(ProductVariant::class, $variant);
                }
            }
        );
    }
}

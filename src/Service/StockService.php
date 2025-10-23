<?php

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

            foreach ($item->attachments as $attachment) {
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
        $product = $item->product->getData();
        /** @var ProductVariant $variant */
        $variant = $item->variant->getData();

        $title = $product->title;

        if (!$variant->primary) {
            $title .= ' - ' . $variant->title;
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
            $variant = $item->variant->getData();

            $quantity = $quantities[$variant->id] ?? 0;

            $variants[$variant->id] = [$variant, $quantity];

            foreach ($item->attachments as $attachment) {
                /** @var ProductVariant $variant */
                $variant = $attachment->variant->getData();

                $quantity = $quantities[$variant->id] ?? 0;

                $variants[$variant->id] = [$variant, $quantity];
            }
        }

        foreach ($variants as [$variant, $quantity]) {
            if ($quantity === 0) {
                continue;
            }

            $mapper->updateBatch(
                ['stock_quantity' => $variant->stockQuantity - $quantity],
                ['id' => $variant->id]
            );
        }
    }

    public function rollbackStocks(Order $order): void
    {
        $this->orm->getDb()->transaction(
            function () use ($order) {
                /** @var OrderItem[] $items */
                $items = $this->orm->from(OrderItem::class)
                    ->where('order_id', $order->id)
                    ->all(OrderItem::class);

                $variantQuantities = [];

                foreach ($items as $item) {
                    $variantQuantities[$item->variantId] ??= 0;

                    $variantQuantities[$item->variantId] += $item->quantity;
                }

                /** @var OrderItem $item */
                foreach ($variantQuantities as $variantId => $quantity) {
                    /** @var ProductVariant $variant */
                    $variant = $this->orm->select()
                        ->from(ProductVariant::class)
                        ->where('id', $variantId)
                        ->forUpdate()
                        ->get(ProductVariant::class);

                    $variant->stockQuantity = $variant->stockQuantity + $quantity;

                    $this->orm->updateOne(ProductVariant::class, $variant);
                }
            }
        );
    }
}

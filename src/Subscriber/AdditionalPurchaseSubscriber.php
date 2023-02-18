<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Subscriber;

use Brick\Math\BigDecimal;
use Lyrasoft\ShopGo\Cart\CartItem;
use Lyrasoft\ShopGo\Cart\Price\PriceObject;
use Lyrasoft\ShopGo\Entity\AdditionalPurchaseAttachment;
use Lyrasoft\ShopGo\Entity\ProductVariant;
use Lyrasoft\ShopGo\Event\AfterComputeTotalsEvent;
use Lyrasoft\ShopGo\Event\BeforeComputeTotalsEvent;
use Lyrasoft\ShopGo\Event\ComputingTotalsEvent;
use Lyrasoft\ShopGo\Event\PrepareCartItemEvent;
use Lyrasoft\ShopGo\Event\PrepareProductPricesEvent;
use Lyrasoft\ShopGo\Service\AdditionalPurchaseService;
use Lyrasoft\ShopGo\Service\VariantService;
use Windwalker\Core\Form\Exception\ValidateFailException;
use Windwalker\Core\Router\Navigator;
use Windwalker\Event\Attributes\EventSubscriber;
use Windwalker\Event\Attributes\ListenTo;
use Windwalker\ORM\Exception\NoResultException;
use Windwalker\ORM\ORM;

/**
 * The AdditionalPurchaseSubscriber class.
 */
#[EventSubscriber]
class AdditionalPurchaseSubscriber
{
    public function __construct(
        protected ORM $orm,
        protected AdditionalPurchaseService $additionalPurchaseService,
        protected Navigator $nav
    ) {
    }

    #[ListenTo(PrepareCartItemEvent::class)]
    public function prepareCartItem(PrepareCartItemEvent $event): void
    {
        $cartItem = $event->getCartItem();

        $item = $event->getStorageItem();
        $product = $event->getProduct();

        if (empty($item['attachments'])) {
            return;
        }

        // Add attachments to primary cart item
        foreach ($item['attachments'] as $attachmentId => $quantity) {
            $attachment = $this->orm->findOne(AdditionalPurchaseAttachment::class, $attachmentId);

            if (!$attachment) {
                continue;
            }

            try {
                [
                    $attachProduct,
                    $attachVariant,
                ] = $this->additionalPurchaseService->validateAttachment($attachment, $product);

                $attachVariant = $this->additionalPurchaseService->prepareVariantView(
                    $attachVariant,
                    $attachProduct,
                    $attachment
                );

                $mainVariant = $this->orm->mustFindOne(
                    ProductVariant::class,
                    ['product_id' => $attachProduct->getId(), 'primary' => 1]
                );

                $priceSet = $attachVariant->getPriceSet();

                $attachCartItem = new CartItem();
                $attachCartItem->setVariant($attachVariant);
                $attachCartItem->setProduct($attachProduct);
                $attachCartItem->setMainVariant($mainVariant);
                $attachCartItem->setOutOfStock(VariantService::isOutOfStock($attachVariant, $attachProduct));
                $attachCartItem->setKey((string) $attachmentId);
                $attachCartItem->setCover($mainVariant->getCover());
                $attachCartItem->setLink(
                    (string) $product->makeLink($this->nav)
                );
                $attachCartItem->setQuantity((int) $quantity);
                $attachCartItem->setPriceSet($priceSet);

                $cartItem->addAttachment($attachCartItem);
            } catch (ValidateFailException | NoResultException $e) {
                continue;
            }
        }
    }

    #[ListenTo(BeforeComputeTotalsEvent::class)]
    public function computeTotals(BeforeComputeTotalsEvent $event): void
    {
        $cartData = $event->getCartData();

        $total = $event->getTotal();

        // We must calc product & attachments total before compute Discounts
        // That DiscountService can get cart total to detect the discount conditions.
        foreach ($cartData->getItems() as $item) {
            $priceSet = $item->getPriceSet();
            $attachmentTotal = new PriceObject('attachments_total', '0');

            foreach ($item->getAttachments() as $attachmentItem) {
                $attachPriceSet = $attachmentItem->getPriceSet();

                $attachmentTotal = $attachmentTotal->plus(
                    $attachPriceSet['final_total'] = $attachPriceSet['final_total']
                        ->multiply($item->getQuantity())
                );
            }

            $total = $total->plus($attachmentTotal);

            $priceSet->set($attachmentTotal);
        }

        $event->setTotal($total);
    }

    #[ListenTo(AfterComputeTotalsEvent::class)]
    public function afterComputeTotals(AfterComputeTotalsEvent $event): void
    {
        $cartData = $event->getCartData();

        $quantities = [];

        foreach ($cartData->getItems() as $item) {
            // After discounted, we re-calc products & attachments total
            $priceSet = $item->getPriceSet();

            $priceSet->add(
                'attached_final_total',
                $priceSet['final_total']->plus($priceSet['attachments_total'])
            );

            $item->setPriceSet($priceSet);

            // Calc quantities
            /** @var ProductVariant $variant */
            $variant = $item->getVariant()->getData();
            $quantity = $quantities[$variant->getId()] ?? 0;

            $quantity += $item->getQuantity();

            $quantities[$variant->getId()] = $quantity;

            foreach ($item->getAttachments() as $attachment) {
                /** @var ProductVariant $variant */
                $variant = $attachment->getVariant()->getData();
                $quantity = $quantities[$variant->getId()] ?? 0;

                $quantity += ($attachment->getQuantity() * $item->getQuantity());

                $quantities[$variant->getId()] = $quantity;
            }
        }

        // Now get out-of-stock items
        foreach ($cartData->getItems() as $item) {
            $product = $item->getProduct()->getData();
            /** @var ProductVariant $variant */
            $variant = $item->getVariant()->getData();

            $quantity = $quantities[$variant->getId()] ?? 1;

            $item->setOutOfStock(VariantService::isOutOfStock($variant, $product, $quantity));

            foreach ($item->getAttachments() as $attachment) {
                /** @var ProductVariant $variant */
                $product = $attachment->getProduct()->getData();
                $variant = $attachment->getVariant()->getData();
                $quantity = $quantities[$variant->getId()] ?? 1;

                $attachment->setOutOfStock(VariantService::isOutOfStock($variant, $product, $quantity));
            }
        }
    }
}

<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Subscriber;

use Lyrasoft\ShopGo\Cart\CartItem;
use Lyrasoft\ShopGo\Entity\AdditionalPurchaseAttachment;
use Lyrasoft\ShopGo\Entity\ProductVariant;
use Lyrasoft\ShopGo\Event\BeforeComputeTotalsEvent;
use Lyrasoft\ShopGo\Event\PrepareCartItemEvent;
use Lyrasoft\ShopGo\Service\AdditionalPurchaseService;
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

                $attachCartItem = new CartItem();
                $attachCartItem->setVariant($attachVariant);
                $attachCartItem->setProduct($attachProduct);
                $attachCartItem->setMainVariant($mainVariant);
                $attachCartItem->setKey((string) $attachmentId);
                $attachCartItem->setCover($mainVariant->getCover());
                $attachCartItem->setLink(
                    (string) $product->makeLink($this->nav)
                );
                $attachCartItem->setQuantity((int) $quantity);
                $attachCartItem->setPriceSet($attachVariant->getPriceSet());

                $cartItem->addAttachment($attachCartItem);
            } catch (ValidateFailException | NoResultException $e) {
                continue;
            }
        }
    }

    #[ListenTo(BeforeComputeTotalsEvent::class)]
    public function beforeComputeTotals(BeforeComputeTotalsEvent $event): void
    {
        // $cartData = $event->getCartData();
        // $totals = $event->getTotals();
        //
        // $total = $totals['total'];
        // $grandTotals = $totals['grand_total'];
        //
        // $cartItems = $cartData->getItems();
        //
        // foreach ($cartItems as $cartItem) {
        //     //
        // }
    }
}

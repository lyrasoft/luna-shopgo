<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Subscriber;

use Lyrasoft\ShopGo\Cart\CartItem;
use Lyrasoft\ShopGo\Cart\Price\PriceObject;
use Lyrasoft\ShopGo\Entity\AdditionalPurchaseAttachment;
use Lyrasoft\ShopGo\Entity\ProductVariant;
use Lyrasoft\ShopGo\Event\AfterComputeTotalsEvent;
use Lyrasoft\ShopGo\Event\BeforeComputeTotalsEvent;
use Lyrasoft\ShopGo\Event\PrepareCartItemEvent;
use Lyrasoft\ShopGo\Service\AdditionalPurchaseService;
use Lyrasoft\ShopGo\Service\VariantService;
use Windwalker\Core\Form\Exception\ValidateFailException;
use Windwalker\Core\Manager\Logger;
use Windwalker\Core\Router\Navigator;
use Windwalker\Event\Attributes\EventSubscriber;
use Windwalker\Event\Attributes\ListenTo;
use Windwalker\ORM\ORM;
use Windwalker\Query\Exception\NoResultException;

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
        $cartItem = $event->cartItem;

        $item = $event->storageItem;
        $product = $event->product;

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
                ] = $this->additionalPurchaseService->validateAttachment($attachment, $product, $event->forUpdate);

                $attachVariant = $this->additionalPurchaseService->prepareVariantView(
                    $attachVariant,
                    $attachProduct,
                    $attachment
                );

                $mainVariant = $this->orm->mustFindOne(
                    ProductVariant::class,
                    ['product_id' => $attachProduct->id, 'primary' => 1]
                );

                $priceSet = $attachVariant->priceSet;

                $attachCartItem = new CartItem(
                    variant: $attachVariant,
                    mainVariant: $mainVariant,
                    product: $attachProduct,
                    priceSet: $priceSet,
                    quantity: (int) $quantity,
                    cover: $attachVariant->cover ?: $mainVariant->cover,
                    link: (string) $product->makeLink($this->nav),
                    key: (string) $attachmentId,
                    outOfStock: VariantService::isOutOfStock($attachVariant, $attachProduct)
                );

                $cartItem->addAttachment($attachCartItem);
            } catch (ValidateFailException|NoResultException $e) {
                Logger::debug('error', (string) $e);
                continue;
            }
        }
    }

    #[ListenTo(BeforeComputeTotalsEvent::class)]
    public function computeTotals(BeforeComputeTotalsEvent $event): void
    {
        $cartData = $event->cartData;

        $total = $event->total;

        // We must calc product & attachments total before compute Discounts
        // That DiscountService can get cart total to detect the discount conditions.
        foreach ($cartData->getItems() as $item) {
            $priceSet = $item->priceSet;
            $attachmentTotal = new PriceObject('attachments_total', '0');

            foreach ($item->attachments as $attachmentItem) {
                $attachPriceSet = $attachmentItem->priceSet;

                $attachmentTotal = $attachmentTotal->plus(
                    $attachPriceSet['final_total'] = $attachPriceSet['final_total']
                        ->multiply($item->quantity)
                );
            }

            $total = $total->plus($attachmentTotal);

            $priceSet->set($attachmentTotal);
        }

        $event->total = $total;
    }

    #[ListenTo(AfterComputeTotalsEvent::class)]
    public function afterComputeTotals(AfterComputeTotalsEvent $event): void
    {
        $cartData = $event->cartData;

        foreach ($cartData->getItems() as $item) {
            // After discounted, we re-calc products & attachments total
            $priceSet = $item->priceSet;

            $priceSet->add(
                'attached_final_total',
                $priceSet['final_total']->plus($priceSet['attachments_total'])
            );

            $item->setPriceSet($priceSet);
        }

        $quantities = $cartData->getTotalQuantities(true, true);

        // Now get out-of-stock items
        foreach ($cartData->getItems() as $item) {
            $product = $item->product->getData();
            /** @var ProductVariant $variant */
            $variant = $item->variant->getData();

            $quantity = $quantities[$variant->id] ?? 1;

            $item->outOfStock = VariantService::isOutOfStock($variant, $product, $quantity);

            foreach ($item->attachments as $attachment) {
                /** @var ProductVariant $variant */
                $product = $attachment->product->getData();
                $variant = $attachment->variant->getData();
                $quantity = $quantities[$variant->id] ?? 1;

                $attachment->setOutOfStock(VariantService::isOutOfStock($variant, $product, $quantity));
            }
        }
    }
}

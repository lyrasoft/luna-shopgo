<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Subscriber;

use Lyrasoft\ShopGo\Cart\CartItem;
use Lyrasoft\ShopGo\Cart\CartStorage;
use Lyrasoft\ShopGo\Data\CartPricingData;
use Lyrasoft\ShopGo\Event\BeforeComputeTotalsEvent;
use Lyrasoft\ShopGo\Event\ComputingTotalsEvent;
use Lyrasoft\ShopGo\Event\PrepareProductPricesEvent;
use Lyrasoft\ShopGo\Service\DiscountService;
use Windwalker\Event\Attributes\EventSubscriber;
use Windwalker\Event\Attributes\ListenTo;

use function Windwalker\collect;
use function Windwalker\uid;

/**
 * The DiscountSubscriber class.
 */
#[EventSubscriber]
class DiscountSubscriber
{
    public function __construct(protected DiscountService $discountService, protected CartStorage $cartStorage)
    {
    }

    #[ListenTo(PrepareProductPricesEvent::class)]
    public function prepareProductPrices(PrepareProductPricesEvent $event): void
    {
        $context = $event->context;

        $this->discountService->computeSingleProductSpecials($event);

        if ($context === $event::CART || $context === $event::ORDER) {
            $cartItem = $event->cartItem;

            $this->discountService->computeSingleProductDiscounts(
                $event,
                $cartItem->quantity,
                $cartItem->isChecked()
            );
        }

        if ($context === $event::PRODUCT_VIEW) {
            $pricing = new CartPricingData();
            $pricing->totals = $event->priceSet;
            $pricing->total = $event->priceSet['final'];
            $pricing->appliedDiscounts = $event->appliedDiscounts;

            $cartItem = $event->cartItem;

            if (!$cartItem) {
                $cartItem = new CartItem(
                    variant: $event->variant,
                    mainVariant: $event->mainVariant,
                    product: $event->product,
                    quantity: 1,
                    key: (string) $event->product->id,
                    uid: uid(),
                    discounts: $event->appliedDiscounts,
                )
                    ->setPriceSet($event->priceSet, false);
            }

            $this->discountService->computeProductsGlobalDiscounts(
                $pricing,
                $this->discountService->getGlobalDiscountsAndAttachedCoupons($this->cartStorage),
                collect([$cartItem])
            );
        }
    }

    #[ListenTo(BeforeComputeTotalsEvent::class)]
    public function beforeComputeTotals(BeforeComputeTotalsEvent $event): void
    {
        // Compute products discounts first to get final product amount.
        $this->discountService->computeProductsGlobalDiscounts(
            $event,
            $this->discountService->getGlobalDiscountsAndAttachedCoupons($this->cartStorage),
            null
        );
    }

    #[ListenTo(ComputingTotalsEvent::class)]
    public function computeTotals(ComputingTotalsEvent $event): void
    {
        // Now we got new product amount, compute order discounts then.
        $this->discountService->computeGlobalDiscounts(
            $event,
            $this->discountService->getGlobalDiscountsAndAttachedCoupons($this->cartStorage)
        );
    }
}

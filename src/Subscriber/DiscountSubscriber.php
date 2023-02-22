<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Subscriber;

use Lyrasoft\ShopGo\Cart\CartStorage;
use Lyrasoft\ShopGo\Event\BeforeComputeTotalsEvent;
use Lyrasoft\ShopGo\Event\ComputingTotalsEvent;
use Lyrasoft\ShopGo\Event\PrepareProductPricesEvent;
use Lyrasoft\ShopGo\Service\DiscountService;
use Windwalker\Event\Attributes\EventSubscriber;
use Windwalker\Event\Attributes\ListenTo;

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
        $context = $event->getContext();

        $this->discountService->computeSingleProductSpecials($event);

        if ($context === $event::CART || $context === $event::ORDER) {
            $cartItem = $event->getCartItem();

            $priceSet = $this->discountService->computeSingleProductDiscounts($event, $cartItem->getQuantity())
                ->getPricing();

            $event->setPricing($priceSet);
        }
    }

    #[ListenTo(BeforeComputeTotalsEvent::class)]
    public function beforeComputeTotals(BeforeComputeTotalsEvent $event): void
    {
        // Compute products discounts first to get final product amount.
        $this->discountService->computeProductsGlobalDiscounts(
            $event,
            $this->discountService->getGlobalDiscountsAndAttachedCoupons($this->cartStorage)
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

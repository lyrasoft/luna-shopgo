<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Subscriber;

use Lyrasoft\ShopGo\Event\BeforeComputeTotalsEvent;
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
    public function __construct(protected DiscountService $discountService)
    {
    }

    #[ListenTo(BeforeComputeTotalsEvent::class)]
    public function beforeComputeTotals(BeforeComputeTotalsEvent $event): void
    {
        $cartData = $event->getCartData();
        $totals = $event->getTotals();

        // Compute Single product discounts
        $cartItems = $cartData->getItems();
    }

    #[ListenTo(PrepareProductPricesEvent::class)]
    public function prepareProductPrices(PrepareProductPricesEvent $event): void
    {
        $context = $event->getContext();

        $priceSet = $this->discountService->computeSingleProductSpecials($event)->getPriceSet();
        $event->setPriceSet($priceSet);

        $cartItem = $event->getCartItem();

        if ($context === $event::CART || $context === $event::ORDER) {
            $priceSet = $this->discountService->computeSingleProductDiscounts($event, $cartItem->getQuantity())
                ->getPriceSet();

            $event->setPriceSet($priceSet);
        }

        $this->discountService->computeGlobalDiscountsForProduct($event);

        $event->setPriceSet($priceSet);
    }
}

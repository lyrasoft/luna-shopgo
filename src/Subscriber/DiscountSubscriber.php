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
        $context = $event->getContext();

        $this->discountService->computeSingleProductSpecials($event);

        if ($context === $event::CART || $context === $event::ORDER) {
            $cartItem = $event->getCartItem();

            $this->discountService->computeSingleProductDiscounts(
                $event,
                $cartItem->getQuantity(),
                $cartItem->isChecked()
            );
        }

        if ($context === $event::PRODUCT_VIEW) {
            $pricing = (new CartPricingData())
                ->setTotals($event->getPriceSet())
                ->setTotal($event->getPriceSet()['final'])
                ->setAppliedDiscounts($event->getAppliedDiscounts());

            $cartItem = $event->getCartItem();

            if (!$cartItem) {
                $cartItem = (new CartItem())
                    ->setProduct($event->getProduct())
                    ->setMainVariant($event->getMainVariant())
                    ->setVariant($event->getVariant())
                    ->setPriceSet($event->getPriceSet(), false)
                    ->setDiscounts($event->getAppliedDiscounts())
                    ->setKey((string) $event->getProduct()->getId())
                    ->setQuantity(1)
                    ->setUid(uid());
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

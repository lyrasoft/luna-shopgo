<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Service;

use Lyrasoft\Luna\Entity\User;
use Lyrasoft\Luna\User\UserService;
use Lyrasoft\ShopGo\Cart\CartItem;
use Lyrasoft\ShopGo\Cart\Price\PriceObject;
use Lyrasoft\ShopGo\Cart\Price\PriceSet;
use Lyrasoft\ShopGo\Data\Contract\CartTotalsInterface;
use Lyrasoft\ShopGo\Data\Contract\ProductPricingInterface;
use Lyrasoft\ShopGo\Entity\Discount;
use Lyrasoft\ShopGo\Entity\Order;
use Lyrasoft\ShopGo\Entity\OrderState;
use Lyrasoft\ShopGo\Entity\Product;
use Lyrasoft\ShopGo\Entity\ShopCategoryMap;
use Lyrasoft\ShopGo\Enum\DiscountApplyTo;
use Lyrasoft\ShopGo\Enum\DiscountCombine;
use Lyrasoft\ShopGo\Enum\DiscountType;
use Lyrasoft\ShopGo\Event\PrepareProductPricesEvent;
use Lyrasoft\ShopGo\Repository\DiscountRepository;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Data\Collection;
use Windwalker\DI\Attributes\Autowire;
use Windwalker\ORM\ORM;
use Windwalker\Utilities\Arr;
use Windwalker\Utilities\Cache\InstanceCacheTrait;

use function Windwalker\chronos;
use function Windwalker\collect;

/**
 * The DiscountService class.
 */
class DiscountService
{
    use InstanceCacheTrait;
    use TranslatorTrait;

    public function __construct(
        #[Autowire]
        protected DiscountRepository $discountRepository,
        protected DiscountUsageService $discountUsageService,
        protected UserService $userService,
        protected ORM $orm,
    ) {
        //
    }

    public function computeGlobalDiscounts(CartTotalsInterface $pricing): void
    {
        foreach ($this->getGlobalDiscounts() as $discount) {
            if (!$this->matchDiscount($discount, $pricing)) {
                continue;
            }

            $r = $this->checkDiscountCombine($discount, $pricing->getAppliedDiscounts());

            if ($r === 'continue') {
                continue;
            }

            if ($r === 'break') {
                break;
            }

            $this->applyDiscount($pricing, $discount);

            if ($discount->getCombine() === DiscountCombine::STOP()) {
                break;
            }
        }
    }

    public function applyDiscount(CartTotalsInterface $pricing, Discount $discount): void
    {
        // Apply
        if ($discount->getApplyTo() === DiscountApplyTo::MATCHED()) {
            $cartItems = $pricing->getMatchedItems()[$discount] ?? [];

            /** @var CartItem $cartItem */
            foreach ($cartItems as $cartItem) {
                $itemApplied = &$cartItem->getDiscounts();
                $priceSet = $cartItem->getPriceSet();

                $r = $this->checkDiscountCombine($discount, $itemApplied);

                if ($r === 'continue') {
                    continue;
                }

                if ($r === 'break') {
                    break;
                }

                if ($itemApplied !== [] && $priceSet['final']->lte((string) $discount->getMinPrice())) {
                    continue;
                }

                $priceSet = $this->addDiscountToProductPrice($priceSet, $discount);

                $cartItem->setPriceSet($priceSet);
                $itemApplied[] = $discount;
            }
        } elseif ($discount->getApplyTo() === DiscountApplyTo::ORDER()) {
            $totals = $pricing->getTotals();
            $grandTotal = PricingService::calcAmount($pricing->getTotal(), $totals);

            PricingService::pricingByDiscount($grandTotal, $discount, $diff);

            $totals->add(
                'discount:' . $discount->getId(),
                $diff,
                $discount->getTitle()
            );
            $pricing->setTotals($totals);

            $cartApplied = $pricing->getCartData()->getDiscounts();
            $cartApplied[] = $discount;
        } elseif ($discount->getApplyTo() === DiscountApplyTo::PRODUCTS()) {
            foreach ($discount->getApplyProducts() as $applyTarget) {
                $cartData = $pricing->getCartData();

                foreach ($cartData->getItems() as $cartItem) {
                    /** @var Product $product */
                    $product = $cartItem->getProduct()->getData()->getId();

                    if ($product->getId() === $applyTarget) {
                        $itemApplied = &$cartItem->getDiscounts();

                        if ($this->checkDiscountCombine($discount, $itemApplied) === true) {
                            $priceSet = $this->addDiscountToProductPrice($cartItem->getPriceSet(), $discount);

                            $cartItem->setPriceSet($priceSet);

                            $itemApplied[] = $discount;
                        }
                    }
                }
            }
        }
    }

    protected function addDiscountToProductPrice(PriceSet $priceSet, Discount $discount): PriceSet
    {
        $newPrice = PricingService::pricingByDiscount($priceSet['final'], $discount, $diff);

        $priceSet->add(
            'discount:' . $discount->getId(),
            $diff,
            $discount->getTitle()
        );
        $priceSet->modify('final', $newPrice);

        return $priceSet;
    }

    public function checkDiscountCombine(Discount $discount, array $applied): string|bool
    {
        foreach ($applied as $appliedDiscount) {
            if ($appliedDiscount->getCombine() === DiscountCombine::STOP()) {
                return 'break';
            }

            if (
                $appliedDiscount->getCombine() === DiscountCombine::INCLUDES()
                && !in_array($discount->getId(), $appliedDiscount->getCombineTargets(), true)
            ) {
                return 'continue';
            }

            if (
                $appliedDiscount->getCombine() === DiscountCombine::EXCLUDES()
                && in_array($discount->getId(), $appliedDiscount->getCombineTargets(), true)
            ) {
                return 'continue';
            }
        }

        return true;
    }

    protected function matchDiscount(Discount $discount, CartTotalsInterface $pricing): bool
    {
        $user = $this->userService->getUser();

        // @ Minimum Discounted Price
        // If a target (order/product/category) has discounts and lower than this price, will be ignored.
        // Todo: Move to discountApply()
        // if ($applied !== [] && $priceSet['final']->lte((string) $discount->getMinPrice())) {
        //     return false;
        // }

        $cartData = $pricing->getCartData();

        // @ Minimum Cart Items
        if ($discount->getMinCartItems()) {
            $cartData = $pricing->getCartData();
            $count = count($cartData->getItems());

            if ($count < $discount->getMinCartItems()) {
                return false;
            }
        }

        // @ Minimum Cart Price
        if ($discount->getMinCartPrice()) {
            $total = $pricing->getTotal();

            if ($total->lt((string) $discount->getMinCartPrice())) {
                return false;
            }
        }

        // @ Times Per User
        if ($discount->getTimesPerUser()) {
            if (!$user->isLogin()) {
                return false;
            }

            $usages = $this->discountUsageService->getUserUsageGroups($user->getId());

            if ($usages >= $discount->getTimesPerUser()) {
                return false;
            }
        }

        // @ First N Times
        if ($discount->getTimesPerUser()) {
            if (!$user->isLogin()) {
                return false;
            }

            $count = $this->orm->select()
                ->selectRaw('COUNT(id) AS count')
                ->from(Order::class)
                ->leftJoin(OrderState::class, 'state', 'state.id', 'order.state')
                ->where('user_id', $user->getId())
                ->where('order.cancelled_at', '!=', null)
                ->where('order.rollback_at', '!=', null)
                ->result();

            if ($count >= $discount->getFirstBuy()) {
                return false;
            }
        }

        // @ Days After Registered
        if ($discount->getAfterRegistered()) {
            /** @var User $user */
            if (!$user->isLogin() || !$user->getRegistered()) {
                return false;
            }

            if (chronos('-' . $discount->getAfterRegistered() . 'days') > $user->getRegistered()) {
                return false;
            }
        }

        // @ Users
        if ($discount->getUsers()) {
            if (!$user->isLogin() || !in_array($user->getId(), $discount->getUsers(), true)) {
                return false;
            }
        }

        // @ Categories
        if ($discount->getCategories()) {
            $matched = [];

            foreach ($cartData->getItems() as $cartItem) {
                /** @var Product $product */
                $product = $cartItem->getProduct()->getData();

                $categoryIds = $this->orm->findColumn(
                    'category_id',
                    ShopCategoryMap::class,
                    ['target_id' => $product->getId(), 'type' => 'product']
                )
                    ->map('intval')
                    ->dump();

                if (array_intersect($categoryIds, $discount->getCategories())) {
                    $pricing->addMatchedItem($discount, $matched[] = $cartItem);
                }
            }

            if ($matched === []) {
                return false;
            }
        }

        // @ Products
        if ($discount->getProducts()) {
            $matched = [];

            foreach ($cartData->getItems() as $cartItem) {
                /** @var Product $product */
                $product = $cartItem->getProduct()->getData();

                if (in_array($product->getId(), $discount->getProducts(), true)) {
                    $pricing->addMatchedItem($discount, $matched[] = $cartItem);
                }
            }

            if ($matched === []) {
                return false;
            }
        }

        // Todo: Payments

        // Todo: Shippings

        return true;
    }

    /**
     * @template T implements ProductPricingInterface
     *
     * @param  T  $pricing
     *
     * @return  T
     *
     * @throws \Brick\Math\Exception\MathException
     */
    public function computeSingleProductDiscounts(
        ProductPricingInterface $pricing,
        int $quantity
    ): ProductPricingInterface {
        // Do not work with other discount.
        $applied = &$pricing->getAppliedDiscounts();

        if (count($applied)) {
            return $pricing;
        }

        $product = $pricing->getProduct();
        $priceSet = $pricing->getPricing();

        $discounts = $this->getProductDiscounts($product->getId());

        $matchedDiscount = null;

        foreach ($discounts as $discount) {
            if ($discount->getMinProductQuantity() <= $quantity) {
                $matchedDiscount = $discount;
            }
        }

        if (!$matchedDiscount) {
            return $pricing;
        }

        PricingService::pricingByDiscount($pricing->getBasePrice(), $matchedDiscount, $diff);

        $prices = $pricing->getPricing();
        $prices->add(
            'product_discount',
            $diff,
            $this->trans('shopgo.total.product.discount')
        );

        $applied[] = $matchedDiscount;

        return $pricing;
    }

    /**
     * @template T implements ProductPricingInterface
     *
     * @param  T  $pricing
     *
     * @return  T
     *
     * @throws \Brick\Math\Exception\MathException
     */
    public function computeSingleProductSpecials(ProductPricingInterface $pricing): ProductPricingInterface
    {
        // Do not work with other discount.
        $applied = &$pricing->getAppliedDiscounts();

        if (count($applied)) {
            return $pricing;
        }

        $product = $pricing->getProduct();
        $priceSet = $pricing->getPricing();

        $specials = $this->getProductSpecials($product->getId());

        // Only apply 1 special
        $special = $specials->first();

        if ($special) {
            PricingService::pricingByDiscount($pricing->getBasePrice(), $special, $diff);

            $priceSet->add(
                'product_special',
                $diff,
                $this->trans('shopgo.total.product.special')
            );

            $applied[] = $special;
        }

        $pricing->setPricing($priceSet);

        return $pricing;
    }

    /**
     * @return  Collection<Discount>
     */
    public function getGlobalDiscounts(): Collection
    {
        return $this->once(
            'discounts.global',
            fn() => $this->discountRepository->getAvailableSelector(DiscountType::GLOBAL())
                ->where('subtype', 'basic')
                ->all(Discount::class)
        );
    }

    /**
     * @param  int  $productId
     *
     * @return  Collection<Discount>
     */
    public function getProductDiscounts(int $productId): Collection
    {
        [$discounts] = $this->getProductDiscountGroups($productId);

        return collect($discounts)
            ->sortBy('minProductQuantity');
    }

    /**
     * @param  int  $productId
     *
     * @return  Collection<Discount>
     */
    public function getProductSpecials(int $productId): Collection
    {
        [, $discounts] = $this->getProductDiscountGroups($productId);

        return collect($discounts);
    }

    /**
     * @param  int  $productId
     *
     * @return  array{ 0: Discount[], 1: Discount[] }
     */
    public function getProductDiscountGroups(int $productId): array
    {
        return $this->once(
            'discount.groups.' . $productId,
            fn() => $this->discountRepository->getProductDiscountGroups($productId)
        );
    }
}

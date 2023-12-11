<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Service;

use Brick\Math\BigDecimal;
use Lyrasoft\Luna\Entity\TagMap;
use Lyrasoft\Luna\Entity\User;
use Lyrasoft\Luna\User\UserService;
use Lyrasoft\ShopGo\Cart\CartItem;
use Lyrasoft\ShopGo\Cart\CartStorage;
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
use Lyrasoft\ShopGo\Enum\DiscountMethod;
use Lyrasoft\ShopGo\Enum\DiscountType;
use Lyrasoft\ShopGo\Repository\DiscountRepository;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Data\Collection;
use Windwalker\DI\Attributes\Autowire;
use Windwalker\ORM\ORM;
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
        protected PricingService $pricingService,
    ) {
        //
    }

    /**
     * @param  CartTotalsInterface      $pricing
     * @param  iterable<Discount>|null  $discounts
     *
     * @return  void
     */
    public function computeGlobalDiscounts(CartTotalsInterface $pricing, ?iterable $discounts = null): void
    {
        $discounts ??= $this->getGlobalDiscounts();

        foreach ($discounts as $discount) {
            if (!$this->matchDiscount($discount, $pricing)) {
                continue;
            }

            if (!$this->checkDiscountCombine($discount, $pricing->getAppliedDiscounts(), $action)) {
                if ($action === 'continue') {
                    continue;
                }

                if ($action === 'break') {
                    break;
                }
            }

            $this->applyCartDiscount($pricing, $discount);

            if ($discount->getCombine() === DiscountCombine::STOP()) {
                break;
            }
        }
    }

    /**
     * @param  CartTotalsInterface  $pricing
     * @param  iterable|null        $discounts
     * @param  iterable|null        $cartItems
     *
     * @return  void
     */
    public function computeProductsGlobalDiscounts(
        CartTotalsInterface $pricing,
        ?iterable $discounts = null,
        ?iterable $cartItems = null
    ): void {
        $discounts ??= $this->getGlobalDiscounts();

        $this->matchProducts($discounts, $pricing, $cartItems);

        $this->applyProductsDiscounts($pricing, $discounts);
    }

    public function applyCartDiscount(CartTotalsInterface $pricing, Discount $discount): void
    {
        // Apply
        if ($discount->getApplyTo() === DiscountApplyTo::ORDER()) {
            if ($discount->getMethod() !== DiscountMethod::NONE()) {
                $totals = $pricing->getTotals();

                if ($discount->isAccumulate()) {
                    $grandTotal = PricingService::calcAmount($pricing->getTotal(), $totals);

                    $this->pricingService->pricingByDiscount($grandTotal, $discount, $diff);
                } else {
                    $this->pricingService->pricingByDiscount($pricing->getTotal(), $discount, $diff);
                }

                $totals->add(
                    'discount:' . $discount->getId(),
                    $diff,
                    $discount->getTitle(),
                    [
                        'id' => $discount->getId(),
                        'type' => $discount->getType(),
                        'subtype' => $discount->getSubtype(),
                        'code' => $discount->getCode(),
                        'title' => $discount->getTitle(),
                    ]
                );
                $pricing->setTotals($totals);
            }

            $cartApplied = &$pricing->getAppliedDiscounts();
            $cartApplied[] = $discount;
        }
    }

    public function applyProductsDiscounts(CartTotalsInterface $pricing, iterable $discounts): void
    {
        $total = $pricing->getTotal();

        foreach ($discounts as $discount) {
            // Apply
            if ($discount->getApplyTo() === DiscountApplyTo::MATCHED()) {
                $cartItems = $pricing->getMatchedItems()[$discount] ?? [];

                /** @var CartItem $cartItem */
                foreach ($cartItems as $cartItem) {
                    $itemApplied = &$cartItem->getDiscounts();
                    $priceSet = $cartItem->getPriceSet();

                    if (!$this->checkDiscountCombine($discount, $itemApplied, $action)) {
                        if ($action === 'continue') {
                            continue;
                        }

                        if ($action === 'break') {
                            break;
                        }
                    }

                    if ($itemApplied !== [] && $priceSet['final']->lte((string) (float) $discount->getMinPrice())) {
                        continue;
                    }

                    /** @var BigDecimal $diff */
                    $priceSet = $this->addDiscountToProductPrice($priceSet, $discount, $diff);

                    $cartItem->setPriceSet($priceSet);

                    if ($cartItem->isChecked()) {
                        $total = $total->plus($diff->multipliedBy($cartItem->getQuantity()));
                        $itemApplied[] = $discount;
                    }
                }
            } elseif ($discount->getApplyTo() === DiscountApplyTo::PRODUCTS()) {
                foreach ($discount->getApplyProducts() as $applyTarget) {
                    $applyTarget = (int) $applyTarget;
                    $cartData = $pricing->getCartData();

                    foreach ($cartData->getCheckedItems() as $cartItem) {
                        /** @var Product $product */
                        $product = $cartItem->getProduct()->getData();

                        if ($product->getId() === $applyTarget) {
                            $itemApplied = &$cartItem->getDiscounts();

                            if ($this->checkDiscountCombine($discount, $itemApplied) === true) {
                                $priceSet = $this->addDiscountToProductPrice(
                                    $cartItem->getPriceSet(),
                                    $discount,
                                    $diff
                                );

                                $cartItem->setPriceSet($priceSet);
                                $total = $total->plus($diff);
                                $itemApplied[] = $discount;
                            }
                        }
                    }
                }
            }
        }

        $pricing->setTotal($total);
    }

    protected function addDiscountToProductPrice(
        PriceSet $priceSet,
        Discount $discount,
        ?BigDecimal &$diff = null
    ): PriceSet {
        if (!$discount->isAccumulate() && $discount->getMethod() === DiscountMethod::PERCENTAGE()) {
            $this->pricingService->pricingByDiscount($priceSet['base'], $discount, $diff);
        } else {
            $this->pricingService->pricingByDiscount($priceSet['final'], $discount, $diff);
        }

        $priceSet['final'] = $priceSet['final']->plus($diff);

        $priceSet->add(
            'discount:' . $discount->getId(),
            $diff,
            $discount->getTitle(),
            [
                'id' => $discount->getId(),
                'type' => $discount->getType(),
                'subtype' => $discount->getSubtype(),
                'code' => $discount->getCode(),
                'title' => $discount->getTitle(),
            ]
        );

        return $priceSet;
    }

    public function checkDiscountCombine(Discount $discount, array $applied, string &$action = null): string|bool
    {
        foreach ($applied as $appliedDiscount) {
            if ($appliedDiscount->getCombine() === DiscountCombine::STOP()) {
                $action = 'break';

                return false;
            }

            if (
                $appliedDiscount->getCombine() === DiscountCombine::INCLUDES()
                && !in_array($discount->getId(), array_map('intval', $appliedDiscount->getCombineTargets()), true)
            ) {
                $action = 'continue';

                return false;
            }

            if (
                $appliedDiscount->getCombine() === DiscountCombine::EXCLUDES()
                && in_array($discount->getId(), array_map('intval', $appliedDiscount->getCombineTargets()), true)
            ) {
                $action = 'continue';

                return false;
            }
        }

        return true;
    }

    public function matchDiscount(Discount $discount, CartTotalsInterface $pricing): bool
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
            $count = count($cartData->getCheckedItems());

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

            $usages = $this->discountUsageService->getUserUsages($user->getId());
            $usage = (int) $usages[$discount->getId()] ?? 0;

            if ($usage >= $discount->getTimesPerUser()) {
                return false;
            }
        }

        // @ First N Times
        if ($discount->getFirstBuy()) {
            if (!$user->isLogin()) {
                return false;
            }

            $count = $this->orm->select()
                ->selectRaw('COUNT(order.id) AS count')
                ->from(Order::class)
                ->leftJoin(OrderState::class, 'state', 'state.id', 'order.state_id')
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
            $userIds = array_map('intval', $discount->getUsers());

            if (!$user->isLogin() || !in_array($user->getId(), $userIds, true)) {
                return false;
            }
        }

        // @ Categories
        if ($discount->getCategories() || $discount->getProducts()) {
            $matched = $pricing->getMatchedItems()[$discount] ?? [];

            if (!$matched) {
                return false;
            }
        }

        // Todo: Payments

        // Todo: Shippings

        return true;
    }

    /**
     * @param  iterable<Discount>   $discounts
     * @param  CartTotalsInterface  $pricing
     * @param  iterable|null        $cartItems
     *
     * @return  CartTotalsInterface
     */
    public function matchProducts(
        iterable $discounts,
        CartTotalsInterface $pricing,
        ?iterable $cartItems = null
    ): CartTotalsInterface {
        $cartItems ??= $pricing->getCartData()->getItems();

        foreach ($discounts as $discount) {
            // @ Categories
            if ($discount->getCategories()) {
                $discountCategoryIds = array_map('intval', $discount->getCategories());

                foreach ($cartItems as $cartItem) {
                    /** @var Product $product */
                    $product = $cartItem->getProduct()->getData();

                    $categoryIds = $this->findProductCategoryIds($product);

                    if (array_intersect($categoryIds, $discountCategoryIds)) {
                        $pricing->addMatchedItem($discount, $cartItem);
                    }
                }
            }

            // @ Tags
            if ($discount->getTags()) {
                $discountTagIds = array_map('intval', $discount->getTags());

                foreach ($cartItems as $cartItem) {
                    /** @var Product $product */
                    $product = $cartItem->getProduct()->getData();

                    $tagIds = $this->findProductTagIds($product);

                    if (array_intersect($tagIds, $discountTagIds)) {
                        $pricing->addMatchedItem($discount, $cartItem);
                    }
                }
            }

            // @ Products
            if ($discount->getProducts()) {
                $productIds = array_map('intval', $discount->getProducts());

                foreach ($cartItems as $cartItem) {
                    /** @var Product $product */
                    $product = $cartItem->getProduct()->getData();

                    if (in_array($product->getId(), $productIds, true)) {
                        $pricing->addMatchedItem($discount, $cartItem);
                    }
                }
            }
        }

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
    public function computeSingleProductDiscounts(
        ProductPricingInterface $pricing,
        int $quantity,
        bool $logDiscounts = true,
    ): ProductPricingInterface {
        // Do not work with other discount.
        $applied = &$pricing->getAppliedDiscounts();

        if (count($applied)) {
            return $pricing;
        }

        $product = $pricing->getProduct();

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

        $priceSet = $pricing->getPriceSet();

        $priceSet['final'] = $this->pricingService->pricingByDiscount($priceSet['final'], $matchedDiscount, $diff);

        $priceSet->add(
            'product_discount',
            $diff,
            $this->trans('shopgo.total.product.discount')
        );

        if ($logDiscounts) {
            $applied[] = $matchedDiscount;
        }

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
        $priceSet = $pricing->getPriceSet();

        $specials = $this->getProductSpecials($product->getId());

        // Only apply 1 special
        $special = $specials->first();

        if ($special) {
            $priceSet['final'] = $this->pricingService->pricingByDiscount($priceSet['final'], $special, $diff);

            $priceSet->add(
                'product_special',
                $diff,
                $this->trans('shopgo.order.total.product.special')
            );

            $applied[] = $special;
        }

        $pricing->setPriceSet($priceSet);

        return $pricing;
    }

    /**
     * @param  string     $code
     * @param  User|null  $user
     *
     * @return  Collection<Discount>
     */
    public function findCodeDiscountsAndCoupons(string $code, ?User $user = null): Collection
    {
        return $this->once(
            'discounts.codes.coupons',
            function () use ($user, $code) {
                $discounts = $this->discountRepository->getAvailableSelector(DiscountType::GLOBAL())
                    ->where('subtype', 'code')
                    ->where('code', $code)
                    ->all(Discount::class);

                /** @var Discount[] $coupons */
                $coupons = $this->discountRepository->getAvailableCouponSelector($code, $user)
                    ->all(Discount::class);

                foreach ($coupons as $coupon) {
                    $coupon->setCode($code);
                }

                return $discounts->merge($coupons);
            }
        );
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
     * @param  CartStorage  $cartStorage
     *
     * @return  Collection<Discount>
     */
    public function getGlobalDiscountsAndAttachedCoupons(CartStorage $cartStorage): Collection
    {
        return $this->once(
            'attached.discounts',
            function () use ($cartStorage) {
                $discounts = $this->getGlobalDiscounts();

                $coupons = $cartStorage->getCoupons();

                if (!$coupons) {
                    return $discounts;
                }

                return $discounts->merge(
                    $this->discountRepository->getAvailableSelector()
                        ->where('id', $coupons)
                        ->all(Discount::class)
                );
            }
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
            function () use ($productId) {
                return $this->discountRepository->groupProductDiscounts(
                    $this->discountRepository->getProductDiscounts($productId)
                );
            }
        );
    }

    public function preloadProductDiscounts(array $productIds): void
    {
        if ($productIds === []) {
            return;
        }

        $productIds = array_unique($productIds);
        
        $discountGroup = $this->discountRepository->getProductDiscounts($productIds)
            ->groupBy('discountId');

        foreach ($productIds as $productId) {
            $discounts = $discountGroup[$productId] ?? collect();

            $this->cacheStorage['discount.groups.' . $productId]
                = $this->discountRepository->groupProductDiscounts($discounts);
        }
    }

    /**
     * @param  Product  $product
     *
     * @return  array<int>
     */
    protected function findProductCategoryIds(Product $product): array
    {
        return $this->once(
            'product.categories.' . $product->getId(),
            fn() => $this->orm->findColumn(
                ShopCategoryMap::class,
                'category_id',
                ['target_id' => $product->getId(), 'type' => 'product']
            )
                ->map('intval')
                ->dump()
        );
    }

    /**
     * @param  Product  $product
     *
     * @return  array<int>
     */
    protected function findProductTagIds(Product $product): array
    {
        return $this->once(
            'product.tags.' . $product->getId(),
            fn() => $this->orm->findColumn(
                TagMap::class,
                'tag_id',
                ['target_id' => $product->getId(), 'type' => 'product']
            )
                ->map('intval')
                ->dump()
        );
    }
}

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

            if (!$this->checkDiscountCombine($discount, $pricing->appliedDiscounts, $action)) {
                if ($action === 'continue') {
                    continue;
                }

                if ($action === 'break') {
                    break;
                }
            }

            $this->applyCartDiscount($pricing, $discount);

            if ($discount->combine === DiscountCombine::STOP) {
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
        if ($discount->applyTo === DiscountApplyTo::ORDER) {
            if ($discount->method !== DiscountMethod::NONE) {
                $totals = $pricing->totals;

                if ($discount->accumulate) {
                    $grandTotal = PricingService::calcAmount($pricing->total, $totals);

                    $this->pricingService->pricingByDiscount($grandTotal, $discount, $diff);
                } else {
                    $this->pricingService->pricingByDiscount($pricing->total, $discount, $diff);
                }

                $totals->add(
                    'discount:' . $discount->id,
                    $diff,
                    $discount->title,
                    [
                        'id' => $discount->id,
                        'type' => $discount->type,
                        'subtype' => $discount->subtype,
                        'code' => $discount->code,
                        'title' => $discount->title,
                    ]
                );
                $pricing->totals = $totals;
            }

            $cartApplied = &$pricing->appliedDiscounts;
            $cartApplied[] = $discount;
        }
    }

    public function applyProductsDiscounts(CartTotalsInterface $pricing, iterable $discounts): void
    {
        $total = $pricing->total;

        foreach ($discounts as $discount) {
            // Apply
            if ($discount->getApplyTo() === DiscountApplyTo::MATCHED) {
                $matchedItems = $pricing->matchedItems;

                $cartItems = $matchedItems[$discount] ?? [];

                /** @var CartItem $cartItem */
                foreach ($cartItems as $cartItem) {
                    $itemApplied = &$cartItem->discounts;
                    $priceSet = $cartItem->priceSet;

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

                    $cartItem->priceSet = $priceSet;

                    if ($cartItem->isChecked()) {
                        $total = $total->plus($diff->multipliedBy($cartItem->quantity));
                        $itemApplied[] = $discount;
                    }
                }
            } elseif ($discount->getApplyTo() === DiscountApplyTo::PRODUCTS) {
                foreach ($discount->getApplyProducts() as $applyTarget) {
                    $applyTarget = (int) $applyTarget;
                    $cartData = $pricing->cartData;

                    foreach ($cartData->getCheckedItems() as $cartItem) {
                        /** @var Product $product */
                        $product = $cartItem->product->getData();

                        if ($product->id === $applyTarget) {
                            $itemApplied = &$cartItem->discounts;

                            if ($this->checkDiscountCombine($discount, $itemApplied) === true) {
                                $priceSet = $this->addDiscountToProductPrice(
                                    $cartItem->priceSet,
                                    $discount,
                                    $diff
                                );

                                $cartItem->priceSet = $priceSet;
                                $total = $total->plus($diff);
                                $itemApplied[] = $discount;
                            }
                        }
                    }
                }
            }
        }

        $pricing->total = $total;
    }

    protected function addDiscountToProductPrice(
        PriceSet $priceSet,
        Discount $discount,
        ?BigDecimal &$diff = null
    ): PriceSet {
        if (!$discount->accumulate && $discount->method === DiscountMethod::PERCENTAGE) {
            $this->pricingService->pricingByDiscount($priceSet['base'], $discount, $diff);
        } else {
            $this->pricingService->pricingByDiscount($priceSet['final'], $discount, $diff);
        }

        $priceSet['final'] = $priceSet['final']->plus($diff);

        $priceSet->add(
            'discount:' . $discount->id,
            $diff,
            $discount->title,
            [
                'id' => $discount->id,
                'type' => $discount->type,
                'subtype' => $discount->subtype,
                'code' => $discount->code,
                'title' => $discount->title,
            ]
        );

        return $priceSet;
    }

    public function checkDiscountCombine(Discount $discount, array $applied, string &$action = null): string|bool
    {
        foreach ($applied as $appliedDiscount) {
            if ($appliedDiscount->getCombine() === DiscountCombine::STOP) {
                $action = 'break';

                return false;
            }

            if (
                $appliedDiscount->getCombine() === DiscountCombine::INCLUDES
                && !in_array($discount->id, array_map('intval', $appliedDiscount->getCombineTargets()), true)
            ) {
                $action = 'continue';

                return false;
            }

            if (
                $appliedDiscount->getCombine() === DiscountCombine::EXCLUDES
                && in_array($discount->id, array_map('intval', $appliedDiscount->getCombineTargets()), true)
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

        $cartData = $pricing->cartData;

        // @ Minimum Cart Items
        if ($discount->minCartItems) {
            $cartData = $pricing->cartData;
            $count = count($cartData->getCheckedItems());

            if ($count < $discount->minCartItems) {
                return false;
            }
        }

        // @ Minimum Cart Price
        if ($discount->minCartPrice) {
            $total = $pricing->total;

            if ($total->lt((string) $discount->minCartPrice)) {
                return false;
            }
        }

        // @ Times Per User
        if ($discount->timesPerUser) {
            if (!$user->isLogin()) {
                return false;
            }

            $usages = $this->discountUsageService->getUserUsages($user->id);
            $usage = (int) $usages[$discount->id] ?? 0;

            if ($usage >= $discount->timesPerUser) {
                return false;
            }
        }

        // @ First N Times
        if ($discount->firstBuy) {
            if (!$user->isLogin()) {
                return false;
            }

            $count = $this->orm->select()
                ->selectRaw('COUNT(order.id) AS count')
                ->from(Order::class)
                ->leftJoin(OrderState::class, 'state', 'state.id', 'order.state_id')
                ->where('user_id', $user->id)
                ->where('order.cancelled_at', '!=', null)
                ->where('order.rollback_at', '!=', null)
                ->result();

            if ($count >= $discount->firstBuy) {
                return false;
            }
        }

        // @ Days After Registered
        if ($discount->afterRegistered) {
            /** @var User $user */
            if (!$user->isLogin() || !$user->registered) {
                return false;
            }

            if (chronos('-' . $discount->afterRegistered . 'days') > $user->registered) {
                return false;
            }
        }

        // @ Users
        if ($discount->users) {
            $userIds = array_map('intval', $discount->users);

            if (!$user->isLogin() || !in_array($user->id, $userIds, true)) {
                return false;
            }
        }

        // @ Categories
        if ($discount->categories || $discount->products) {
            $matchedItems = $pricing->matchedItems;
            $matched = $matchedItems[$discount] ?? [];

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
        $cartItems ??= $pricing->cartData->getItems();

        foreach ($discounts as $discount) {
            // @ Categories
            if ($discount->categories) {
                $discountCategoryIds = array_map('intval', $discount->categories);

                foreach ($cartItems as $cartItem) {
                    /** @var Product $product */
                    $product = $cartItem->product->getData();

                    $categoryIds = $this->findProductCategoryIds($product);

                    if (array_intersect($categoryIds, $discountCategoryIds)) {
                        $pricing->addMatchedItem($discount, $cartItem);
                    }
                }
            }

            // @ Tags
            if ($discount->tags) {
                $discountTagIds = array_map('intval', $discount->tags);

                foreach ($cartItems as $cartItem) {
                    /** @var Product $product */
                    $product = $cartItem->product->getData();

                    $tagIds = $this->findProductTagIds($product);

                    if (array_intersect($tagIds, $discountTagIds)) {
                        $pricing->addMatchedItem($discount, $cartItem);
                    }
                }
            }

            // @ Products
            if ($discount->products) {
                $productIds = array_map('intval', $discount->products);

                foreach ($cartItems as $cartItem) {
                    /** @var Product $product */
                    $product = $cartItem->product->getData();

                    if (in_array($product->id, $productIds, true)) {
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
        $applied = &$pricing->appliedDiscounts;

        if (count($applied)) {
            return $pricing;
        }

        $product = $pricing->getProduct();

        $discounts = $this->getProductDiscounts($product->id);

        $matchedDiscount = null;

        foreach ($discounts as $discount) {
            if ($discount->minProductQuantity <= $quantity) {
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
        $applied = &$pricing->appliedDiscounts;

        if (count($applied)) {
            return $pricing;
        }

        $product = $pricing->getProduct();
        $priceSet = $pricing->getPriceSet();

        $specials = $this->getProductSpecials($product->id);

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
                $discounts = $this->discountRepository->getAvailableSelector(DiscountType::GLOBAL)
                    ->where('subtype', 'code')
                    ->where('code', $code)
                    ->all(Discount::class);

                /** @var Discount[] $coupons */
                $coupons = $this->discountRepository->getAvailableCouponSelector($code, $user)
                    ->all(Discount::class);

                foreach ($coupons as $coupon) {
                    $coupon->code = $code;
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
            fn() => $this->discountRepository->getAvailableSelector(DiscountType::GLOBAL)
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
            'product.categories.' . $product->id,
            fn() => $this->orm->findColumn(
                ShopCategoryMap::class,
                'category_id',
                ['target_id' => $product->id, 'type' => 'product']
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
            'product.tags.' . $product->id,
            fn() => $this->orm->findColumn(
                TagMap::class,
                'tag_id',
                ['target_id' => $product->id, 'type' => 'product']
            )
                ->map('intval')
                ->dump()
        );
    }
}

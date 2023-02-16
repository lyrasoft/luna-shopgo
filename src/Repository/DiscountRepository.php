<?php

/**
 * Part of starter project.
 *
 * @copyright    Copyright (C) 2021 __ORGANIZATION__.
 * @license        MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Repository;

use Lyrasoft\ShopGo\Entity\Discount;
use Lyrasoft\ShopGo\Entity\DiscountUsage;
use Lyrasoft\ShopGo\Enum\DiscountType;
use Lyrasoft\ShopGo\Service\DiscountUsageService;
use Unicorn\Attributes\ConfigureAction;
use Unicorn\Attributes\Repository;
use Unicorn\Repository\Actions\BatchAction;
use Unicorn\Repository\Actions\ReorderAction;
use Unicorn\Repository\Actions\SaveAction;
use Unicorn\Repository\ListRepositoryInterface;
use Unicorn\Repository\ListRepositoryTrait;
use Unicorn\Repository\ManageRepositoryInterface;
use Unicorn\Repository\ManageRepositoryTrait;
use Unicorn\Selector\ListSelector;
use Windwalker\Query\Query;

use function Windwalker\chronos;

/**
 * The DiscountRepository class.
 */
#[Repository(entityClass: Discount::class)]
class DiscountRepository implements ManageRepositoryInterface, ListRepositoryInterface
{
    use ManageRepositoryTrait;
    use ListRepositoryTrait;

    public function getListSelector(?DiscountType $type = null): ListSelector
    {
        $selector = $this->createSelector();

        $selector->from(Discount::class)
            ->selectRaw('IFNULL(usage.count, 0) AS total_usages')
            ->leftJoin(
                fn(Query $query) => $query->selectRaw('COUNT(*) AS count')
                    ->select('discount_id')
                    ->from(DiscountUsage::class)
                    ->group('discount_id'),
                'usage',
                'usage.discount_id',
                'discount.id',
            );

        if ($type) {
            $selector->where('discount.type', $type);
        }

        return $selector;
    }

    public function getAvailableSelector(?DiscountType $type = null): ListSelector
    {
        $selector = $this->getListSelector($type);

        $selector->where('discount.state', 1);

        $now = chronos();
        $selector->orWhere(
            function (Query $query) use ($now) {
                $query->where('discount.publish_up', null);
                $query->where('discount.publish_up', '<', $now);
            }
        );
        $selector->orWhere(
            function (Query $query) use ($now) {
                $query->where('discount.publish_down', null);
                $query->where('discount.publish_down', '>=', $now);
            }
        );
        $selector->orWhere(
            function (Query $query) {
                $query->where('discount.quantity', null);
                $query->where('discount.quantity', 0);
                $query->whereRaw('usage.count < discount.quantity');
            }
        );

        $selector->order('discount.ordering', 'ASC');
        $selector->limit(0);

        return $selector;
    }

    /**
     * @return  array{ 0: Discount[], 1: Discount[] }
     */
    public function getProductDiscountGroups(int $productId): array
    {
        $discounts = $this->getAvailableSelector(DiscountType::PRODUCT())
            ->where('subtype', ['discount', 'special'])
            ->where('product_id', $productId)
            ->all(Discount::class);

        $productDiscounts = [];
        $productSpecials = [];

        /** @var Discount $discount */
        foreach ($discounts as $discount) {
            if ($discount->getSubtype() === 'discount') {
                $productDiscounts[] = $discount;
            } else {
                $productSpecials[] = $discount;
            }
        }

        return [$productDiscounts, $productSpecials];
    }

    public function getCouponListSelector(): ListSelector
    {
        return $this->getListSelector(DiscountType::COUPON());
    }

    #[ConfigureAction(SaveAction::class)]
    protected function configureSaveAction(SaveAction $action): void
    {
        //
    }

    #[ConfigureAction(ReorderAction::class)]
    protected function configureReorderAction(ReorderAction $action): void
    {
        //
    }

    #[ConfigureAction(BatchAction::class)]
    protected function configureBatchAction(BatchAction $action): void
    {
        //
    }
}

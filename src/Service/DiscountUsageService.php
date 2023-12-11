<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Service;

use Lyrasoft\ShopGo\Entity\Discount;
use Lyrasoft\ShopGo\Entity\DiscountUsage;
use Windwalker\Data\Collection;
use Windwalker\ORM\ORM;
use Windwalker\ORM\SelectorQuery;
use Windwalker\Query\Query;
use Windwalker\Utilities\Cache\InstanceCacheTrait;

use function Windwalker\chronos;
use function Windwalker\collect;

/**
 * The DiscountUsageService class.
 */
class DiscountUsageService
{
    use InstanceCacheTrait;

    public function __construct(protected ORM $orm)
    {
    }

    /**
     * @param  int    $userId
     *
     * @return  Collection<int>
     */
    public function getUserUsages(int $userId): Collection
    {
        return $this->once(
            'user.usages.' . $userId,
            fn() => $this->loadUserUsage($userId)
                ->selectRaw('COUNT(discount_usage.id) AS count')
                ->group('discount_usage.discount_id')
                ->all()
                ->mapWithKeys(fn ($item) => [$item->discount_id => $item->count])
        );
    }

    /**
     * @param  int    $userId
     *
     * @return  Collection<int>
     */
    public function getUserUsageGroups(int $userId): Collection
    {
        return $this->once(
            'user.usage.groups.' . $userId,
            fn () => $this->loadUserUsage($userId)
                ->all()
                ->groupBy('discount_id')
        );
    }

    /**
     * @param  int  $userId
     * @param  int  $discountId
     *
     * @return  int
     */
    public function getUserUsagesOfDiscount(int $userId, int $discountId): int
    {
        return (int) $this->getUserUsageGroups($userId)[$discountId] ?? 0;
    }

    /**
     * @param  int  $userId
     *
     * @return  SelectorQuery
     */
    protected function loadUserUsage(int $userId): SelectorQuery
    {
        return $this->orm->select('discount_usage.discount_id')
            ->from(DiscountUsage::class, 'discount_usage')
            ->leftJoin(Discount::class, 'discount')
            ->where('discount.state', 1)
            ->orWhere(
                function (Query $query) {
                    $query->where('discount.publish_up', null);
                    $query->where('discount.publish_up', '<', chronos());
                }
            )
            ->orWhere(
                function (Query $query) {
                    $query->where('discount.publish_down', null);
                    $query->where('discount.publish_down', '>=', chronos());
                }
            )
            ->where('discount_usage.user_id', $userId);
    }
}

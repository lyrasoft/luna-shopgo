<?php

/**
 * Part of starter project.
 *
 * @copyright    Copyright (C) 2021 __ORGANIZATION__.
 * @license        __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Repository;

use Lyrasoft\ShopGo\Entity\AdditionalPurchase;
use Lyrasoft\ShopGo\Entity\AdditionalPurchaseMap;
use Lyrasoft\ShopGo\Entity\Product;
use Lyrasoft\ShopGo\Entity\ProductVariant;
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

/**
 * The AdditionalPurchaseRepository class.
 */
#[Repository(entityClass: AdditionalPurchase::class)]
class AdditionalPurchaseRepository implements ManageRepositoryInterface, ListRepositoryInterface
{
    use ManageRepositoryTrait;
    use ListRepositoryTrait;

    public function getListSelector(): ListSelector
    {
        $selector = $this->createSelector();

        $selector->from(AdditionalPurchase::class)
            ->leftJoin(
                ProductVariant::class,
                'variant',
                'additional_purchase.attach_variant_id',
                'variant.id',
            )
            ->leftJoin(
                Product::class,
                'product',
                'additional_purchase.attach_product_id',
                'product.id',
            )
            ->selectRaw('IFNULL(map.count, 0) AS target_product_counts')
            ->leftJoin(
                fn(Query $query) => $query->select('additional_purchase_id')
                    ->selectRaw('COUNT(*) AS count')
                    ->from(AdditionalPurchaseMap::class)
                    ->group('additional_purchase_id'),
                'map',
                'map.additional_purchase_id',
                'additional_purchase.id'
            )
            ->where('variant.id', '!=', null)
            ->where('product.id', '!=', null);

        return $selector;
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

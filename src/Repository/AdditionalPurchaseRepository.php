<?php

/**
 * Part of starter project.
 *
 * @copyright    Copyright (C) 2021 __ORGANIZATION__.
 * @license        MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Repository;

use Lyrasoft\ShopGo\Entity\AdditionalPurchase;
use Lyrasoft\ShopGo\Entity\AdditionalPurchaseAttachment;
use Lyrasoft\ShopGo\Entity\AdditionalPurchaseTarget;
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
            ->selectRaw('IFNULL(map.count, 0) AS target_product_counts')
            ->leftJoin(
                fn(Query $query) => $query->select('additional_purchase_id')
                    ->selectRaw('COUNT(*) AS count')
                    ->from(AdditionalPurchaseTarget::class)
                    ->group('additional_purchase_id'),
                'map',
                'map.additional_purchase_id',
                'additional_purchase.id'
            );

        $selector->addFilterHandler(
            'attachment',
            function (Query $query, string $field, mixed $value) {
                if ((string) $value !== '') {
                    $query->whereExists(
                        fn (Query $query) => $query->from(AdditionalPurchaseAttachment::class)
                            ->whereRaw('product_id = %a', (int) $value)
                            ->whereRaw('additional_purchase_id = additional_purchase.id')
                    );
                }
            }
        );

        $selector->addFilterHandler(
            'target',
            function (Query $query, string $field, mixed $value) {
                if ((string) $value !== '') {
                    $query->whereExists(
                        fn (Query $query) => $query->from(AdditionalPurchaseTarget::class)
                            ->whereRaw('product_id = %a', (int) $value)
                            ->whereRaw('additional_purchase_id = additional_purchase.id')
                    );
                }
            }
        );

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

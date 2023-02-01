<?php

/**
 * Part of starter project.
 *
 * @copyright    Copyright (C) 2021 __ORGANIZATION__.
 * @license        __LICENSE__
 */

declare(strict_types=1);

namespace Lyraoft\ShopGo\Repository;

use Lyraoft\ShopGo\Entity\Product;
use Lyraoft\ShopGo\Entity\ProductVariant;
use Lyraoft\ShopGo\Entity\ShopCategoryMap;
use Lyrasoft\Luna\Entity\Category;
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

use function Windwalker\Query\val;

/**
 * The ProductRepository class.
 */
#[Repository(entityClass: Product::class)]
class ProductRepository implements ManageRepositoryInterface, ListRepositoryInterface
{
    use ManageRepositoryTrait;
    use ListRepositoryTrait;

    public function getListSelector(): ListSelector
    {
        $selector = $this->createSelector();

        $selector->from(Product::class)
            ->selectRaw('IFNULL(variants.count, 0) AS variants_count')
            ->selectRaw('IFNULL(variants.max_price, 0) AS max_price')
            ->selectRaw('IFNULL(variants.min_price, 0) AS min_price')
            ->selectRaw('IFNULL(variants.variants_stock_quantity, 0) AS variants_stock_quantity')
            ->selectRaw('IF(variants.count = 0, variant.stock_quantity, IFNULL(variants.variants_stock_quantity, 0)) AS total_stock_quantity')
             ->leftJoin(
                 ProductVariant::class,
                 'variant',
                 [
                     ['variant.product_id', 'product.id'],
                     ['variant.primary', val(1)]
                 ]
             )
             ->leftJoin(
                 fn(Query $subQuery) => $subQuery->selectRaw('COUNT(*) AS count')
                     ->selectRaw('MAX(price) AS max_price')
                     ->selectRaw('MIN(price) AS min_price')
                     ->selectRaw('SUM(stock_quantity) AS variants_stock_quantity')
                     ->select('product_id')
                     ->from(ProductVariant::class)
                     ->where('primary', '!=', 1)
                     ->group('product_id'),
                 'variants',
                 'variants.product_id',
                 'product.id',
             )
            ->leftJoin(Category::class);

        $selector->addFilterHandler(
            'category_id',
            function (Query $query, string $field, mixed $value) {
                if ((string) $value !== '') {
                    $query->whereExists(
                        fn(Query $query) => $query->from(ShopCategoryMap::class)
                            ->whereRaw('target_id = product.id')
                            ->whereRaw('type = %q', 'product')
                            ->whereRaw('category_id = %a', (int) $value)
                    );
                }
            }
        );

        return $selector;
    }

    #[ConfigureAction(SaveAction::class)]
    protected function configureSaveAction(SaveAction $action): void
    {
        $this->newOrderFirst($action);
    }

    #[ConfigureAction(ReorderAction::class)]
    protected function configureReorderAction(ReorderAction $action): void
    {
        $action->setEntityClass(ShopCategoryMap::class);

        $action->setReorderGroupHandler(
            function (Query $query, ShopCategoryMap $entity) {
                $query->where('category_id', $entity->getCategoryId());
                $query->where('type', $entity->getType());
            }
        );
    }

    #[ConfigureAction(BatchAction::class)]
    protected function configureBatchAction(BatchAction $action): void
    {
        //
    }
}

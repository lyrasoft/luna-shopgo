<?php

/**
 * Part of starter project.
 *
 * @copyright    Copyright (C) 2021 __ORGANIZATION__.
 * @license        MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Repository;

use Lyrasoft\Favorite\Entity\Favorite;
use Lyrasoft\Favorite\Repository\FavoriteRepository;
use Lyrasoft\Luna\Entity\Category;
use Lyrasoft\Luna\Entity\User;
use Lyrasoft\ShopGo\Entity\Product;
use Lyrasoft\ShopGo\Entity\ProductVariant;
use Lyrasoft\ShopGo\Entity\ShopCategoryMap;
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
use function Windwalker\Query\val;

/**
 * The ProductRepository class.
 */
#[Repository(entityClass: Product::class)]
class ProductRepository implements ManageRepositoryInterface, ListRepositoryInterface
{
    use ManageRepositoryTrait;
    use ListRepositoryTrait;

    public function getFrontListSelector(?User $user = null): ListSelector
    {
        $selector = $this->getListSelector();

        if ($user && $user->isLogin()) {
            FavoriteRepository::joinFavorite(
                $selector,
                'product',
                $user->getId(),
                'product.id'
            );
        }

        $selector->where('product.state', 1);
        $selector->where('category.state', 1);

        $now = chronos();
        $selector->orWhere(
            function (Query $query) use ($now) {
                $query->where('product.publish_up', null);
                $query->where('product.publish_up', '<', $now);
            }
        );
        $selector->orWhere(
            function (Query $query) use ($now) {
                $query->where('product.publish_down', null);
                $query->where('product.publish_down', '>=', $now);
            }
        );

        return $selector;
    }

    public function getListSelector(): ListSelector
    {
        $selector = $this->createSelector();

        $selector->from(Product::class)
            ->selectRaw('IFNULL(variants.count, 0) AS variants_count')
            ->selectRaw('IFNULL(variants.max_price, 0) AS max_price')
            ->selectRaw('IFNULL(variants.min_price, 0) AS min_price')
            ->selectRaw('IFNULL(variants.max_price, 0) AS max_price')
            ->selectRaw('IFNULL(variants.min_stock, 0) AS min_stock')
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
                     ->selectRaw('MAX(stock_quantity) AS max_stock')
                     ->selectRaw('MIN(stock_quantity) AS min_stock')
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

<?php

/**
 * Part of starter project.
 *
 * @copyright    Copyright (C) 2021 __ORGANIZATION__.
 * @license        MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Repository;

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

use function Windwalker\Query\val;

/**
 * The ProductVariantRepository class.
 */
#[Repository(entityClass: ProductVariant::class)]
class ProductVariantRepository implements ManageRepositoryInterface, ListRepositoryInterface
{
    use ManageRepositoryTrait;
    use ListRepositoryTrait;

    public function getCartListSelector(): ListSelector
    {
        $selector = $this->getListSelector();

        $selector->leftJoin(Product::class, 'product', 'product.id', 'product_variant.product_id')
            ->leftJoin(
                ProductVariant::class,
                'main_variant',
                [
                    ['main_variant.product_id', 'product.id'],
                    ['main_variant.primary', val(1)]
                ]
            )
            ->where('product_variant.state', 1)
            ->where('product.state', 1);

        return $selector;
    }

    public function getListSelector(): ListSelector
    {
        $selector = $this->createSelector();

        $selector->from(ProductVariant::class);

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

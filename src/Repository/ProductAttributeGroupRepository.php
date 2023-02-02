<?php

/**
 * Part of starter project.
 *
 * @copyright      Copyright (C) 2021 __ORGANIZATION__.
 * @license        MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Repository;

use Lyrasoft\ShopGo\Entity\ShopCategoryMap;
use Lyrasoft\Luna\Entity\Category;
use Lyrasoft\Luna\Repository\CategoryRepository;
use Unicorn\Attributes\Repository;
use Unicorn\Selector\ListSelector;
use Windwalker\Query\Query;

/**
 * The ProductAttributeGroupRepository class.
 */
#[Repository(entityClass: Category::class)]
class ProductAttributeGroupRepository extends CategoryRepository
{
    public function getListSelector(): ListSelector
    {
        $selector = parent::getListSelector();

        $selector->addFilterHandler(
            'category',
            function (Query $query, string $field, mixed $value) {
                if ((string) $value !== '') {
                    $query->whereExists(
                        fn(Query $query) => $query->from(ShopCategoryMap::class)
                            ->whereRaw('target_id = category.id')
                            ->where('type', 'attribute_group')
                            ->where('category_id', $value)
                    );
                }
            }
        );

        return $selector;
    }
}

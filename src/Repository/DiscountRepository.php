<?php

/**
 * Part of starter project.
 *
 * @copyright    Copyright (C) 2021 __ORGANIZATION__.
 * @license        __LICENSE__
 */

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Discount;
use App\Enum\DiscountType;
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
use Windwalker\ORM\SelectorQuery;

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

        $selector->from(Discount::class);

        if ($type) {
            $selector->where('discount.type', DiscountType::GLOBAL());
        }

        return $selector;
    }

    public function getGlobalListSelector(): ListSelector
    {
        return $this->getListSelector(DiscountType::GLOBAL());
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

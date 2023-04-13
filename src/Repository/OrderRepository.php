<?php

/**
 * Part of starter project.
 *
 * @copyright    Copyright (C) 2021 __ORGANIZATION__.
 * @license        MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Repository;

use Lyrasoft\Luna\Entity\User;
use Lyrasoft\ShopGo\Entity\Order;
use Lyrasoft\ShopGo\Entity\OrderState;
use Lyrasoft\ShopGo\Entity\Payment;
use Lyrasoft\ShopGo\Entity\Shipping;
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
 * The OrderRepository class.
 */
#[Repository(entityClass: Order::class)]
class OrderRepository implements ManageRepositoryInterface, ListRepositoryInterface
{
    use ManageRepositoryTrait;
    use ListRepositoryTrait;

    public function getListSelector(): ListSelector
    {
        $selector = $this->createSelector();

        $selector->from(Order::class)
            ->leftJoin(OrderState::class)
            ->leftJoin(
                Shipping::class,
                'ship',
                'ship.id',
                'order.shipping_id'
            )
            ->leftJoin(Payment::class, 'pay');

        $selector->addFilterHandler(
            'start_date',
            function (Query $query, string $field, mixed $value) {
                if ((string) $value !== '') {
                    $query->where('order.created', '>=', $value);
                }
            }
        );

        $selector->addFilterHandler(
            'end_date',
            function (Query $query, string $field, mixed $value) {
                if ((string) $value !== '') {
                    $query->where('order.created', '<=', $value);
                }
            }
        );

        return $selector;
    }

    public function getFrontListSelector(User $user): ListSelector
    {
        $selector = $this->getListSelector();

        $selector->where('user_id', $user->getId());

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

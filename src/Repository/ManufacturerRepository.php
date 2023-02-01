<?php

/**
 * Part of starter project.
 *
 * @copyright      Copyright (C) 2021 __ORGANIZATION__.
 * @license        __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Repository;

use Lyrasoft\ShopGo\Entity\Manufacturer;
use Lyrasoft\Luna\Entity\Language;
use Lyrasoft\Luna\Entity\User;
use Lyrasoft\Luna\Locale\LocaleAwareTrait;
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
use Windwalker\DI\Attributes\Inject;
use Windwalker\DI\Container;

/**
 * The ManufacturerRepository class.
 */
#[Repository(entityClass: Manufacturer::class)]
class ManufacturerRepository implements ManageRepositoryInterface, ListRepositoryInterface
{
    use LocaleAwareTrait;
    use ManageRepositoryTrait;
    use ListRepositoryTrait;

    #[Inject]
    protected Container $container;

    public function getListSelector(): ListSelector
    {
        $selector = $this->createSelector();

        $selector->from(Manufacturer::class)
            ->leftJoin(User::class);

        if ($this->localeService->isEnabled()) {
            $selector->leftJoin(Language::class, 'lang', 'lang.code', 'manufacturer.language');
        }

        return $selector;
    }

    #[ConfigureAction(SaveAction::class)]
    protected function configureSaveAction(SaveAction $action): void
    {
        $this->newOrderLast($action);
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

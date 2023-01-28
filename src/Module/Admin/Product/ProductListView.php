<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Module\Admin\Product;

use App\Entity\ShopCategoryMap;
use App\Module\Admin\Product\Form\GridForm;
use App\Repository\ProductRepository;
use App\Traits\CurrencyAwareTrait;
use Unicorn\Selector\ListSelector;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Attributes\ViewModel;
use Windwalker\Core\Form\FormFactory;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Core\View\View;
use Windwalker\Core\View\ViewModelInterface;
use Windwalker\Data\Collection;
use Windwalker\DI\Attributes\Autowire;
use Windwalker\ORM\ORM;

use function Windwalker\Query\val;

/**
 * The ProductListView class.
 */
#[ViewModel(
    layout: [
        'default' => 'product-list',
        'modal' => 'product-modal',
    ],
    js: 'product-list.js'
)]
class ProductListView implements ViewModelInterface
{
    use TranslatorTrait;
    use CurrencyAwareTrait;

    public function __construct(
        protected ORM $orm,
        #[Autowire]
        protected ProductRepository $repository,
        protected FormFactory $formFactory
    ) {
    }

    /**
     * Prepare view data.
     *
     * @param  AppContext  $app  The request app context.
     * @param  View        $view  The view object.
     *
     * @return  array
     */
    public function prepare(AppContext $app, View $view): array
    {
        $state = $this->repository->getState();

        // Prepare Items
        $page = $state->rememberFromRequest('page');
        $limit = $state->rememberFromRequest('limit');
        $filter = (array) $state->rememberFromRequest('filter');
        $search = (array) $state->rememberFromRequest('search');

        $hasCategoryFilter = (bool) $filterCategoryId = ($filter['category_id'] ?? null);

        $ordering = $state->rememberFromRequest('list_ordering') ?? $this->getDefaultOrdering($hasCategoryFilter);

        $items = $this->repository->getListSelector()
            ->setFilters($filter)
            ->searchTextFor(
                $search['*'] ?? '',
                $this->getSearchFields()
            )
            ->tapIf(
                $hasCategoryFilter,
                fn(ListSelector $selector) => $selector->leftJoin(
                    ShopCategoryMap::class,
                    'category_map',
                    [
                        ['category_map.target_id', '=', 'product.id'],
                        ['category_map.type', '=', val('product')],
                        ['category_map.category_id', '=', val($filterCategoryId)],
                    ]
                )
            )
            ->ordering($ordering)
            ->page($page)
            ->limit($limit);

        $pagination = $items->getPagination();

        // Prepare Form
        $form = $this->formFactory->create(GridForm::class);
        $form->fill(compact('search', 'filter'));

        $showFilters = $this->showFilterBar($filter);

        $this->prepareMetadata($app, $view);

        return compact(
            'items',
            'pagination',
            'form',
            'showFilters',
            'ordering',
            'hasCategoryFilter'
        );
    }

    public function prepareItem(Collection $item): object
    {
        return $this->repository->getEntityMapper()->toEntity($item);
    }

    /**
     * Get default ordering.
     *
     * @param  bool  $hasCategoryFilter
     *
     * @return  string
     */
    public function getDefaultOrdering(bool $hasCategoryFilter): string
    {
        if (!$hasCategoryFilter) {
            return 'product.id DESC';
        }

        return 'category_map.ordering ASC';
    }

    /**
     * Get search fields.
     *
     * @return  string[]
     */
    public function getSearchFields(): array
    {
        return [
            'product.id',
            'product.title',
            'product.alias',
            'product.search_index',
        ];
    }

    /**
     * Is reorder enabled.
     *
     * @param  string  $ordering
     *
     * @return  bool
     */
    public function reorderEnabled(string $ordering): bool
    {
        return $ordering === 'category_map.ordering ASC';
    }

    /**
     * Can show Filter bar
     *
     * @param  array  $filter
     *
     * @return  bool
     */
    public function showFilterBar(array $filter): bool
    {
        foreach ($filter as $value) {
            if ($value !== null && (string) $value !== '') {
                return true;
            }
        }

        return false;
    }

    /**
     * Prepare Metadata and HTML Frame.
     *
     * @param  AppContext  $app
     * @param  View        $view
     *
     * @return  void
     */
    protected function prepareMetadata(AppContext $app, View $view): void
    {
        $view->getHtmlFrame()
            ->setTitle(
                $this->trans('unicorn.title.grid', title: 'Product')
            );
    }
}

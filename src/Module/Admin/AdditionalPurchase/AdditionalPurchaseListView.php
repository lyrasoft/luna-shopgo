<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Module\Admin\AdditionalPurchase;

use Lyrasoft\ShopGo\Entity\AdditionalPurchase;
use Lyrasoft\ShopGo\Entity\AdditionalPurchaseAttachment;
use Lyrasoft\ShopGo\Entity\Product;
use Lyrasoft\ShopGo\Entity\ProductVariant;
use Lyrasoft\ShopGo\Module\Admin\AdditionalPurchase\Form\GridForm;
use Lyrasoft\ShopGo\Repository\AdditionalPurchaseRepository;
use Lyrasoft\ShopGo\Traits\CurrencyAwareTrait;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Attributes\ViewModel;
use Windwalker\Core\Form\FormFactory;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Core\View\View;
use Windwalker\Core\View\ViewModelInterface;
use Windwalker\Data\Collection;
use Windwalker\DI\Attributes\Autowire;
use Windwalker\ORM\ORM;
use Windwalker\Query\Query;

use function Windwalker\Query\val;

/**
 * The AdditionalPurchaseListView class.
 */
#[ViewModel(
    layout: [
        'default' => 'additional-purchase-list',
        'modal' => 'additional-purchase-modal',
    ],
    js: 'additional-purchase-list.js'
)]
class AdditionalPurchaseListView implements ViewModelInterface
{
    use TranslatorTrait;
    use CurrencyAwareTrait;

    public function __construct(
        protected ORM $orm,
        #[Autowire]
        protected AdditionalPurchaseRepository $repository,
        protected FormFactory $formFactory
    ) {
    }

    /**
     * Prepare view data.
     *
     * @param  AppContext  $app   The request app context.
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
        $ordering = $state->rememberFromRequest('list_ordering') ?? $this->getDefaultOrdering();

        $items = $this->repository->getListSelector()
            ->setFilters($filter)
            ->searchTextFor(
                $search['*'] ?? '',
                $this->getSearchFields()
            )
            ->ordering($ordering)
            ->page($page)
            ->limit($limit);

        $pagination = $items->getPagination();

        $items = $items->all();

        $ids = $items->column('id')->dump();

        // Attachments
        $productSet = $this->orm->select('attachment.additional_purchase_id AS additional_purchase_id')
            ->select('attachment.count AS attachment_count')
            ->from(Product::class)
            ->leftJoin(
                ProductVariant::class,
                'variant',
                [
                    ['variant.product_id', 'product.id'],
                    ['variant.primary', val(1)],
                ]
            )
            ->leftJoin(
                fn (Query $query) => $query->select(
                    'product_id',
                    'additional_purchase_id'
                )
                    ->selectRaw('SUM(IF(state = 1, 1, 0)) AS count')
                    ->from(AdditionalPurchaseAttachment::class)
                    ->group('additional_purchase_id', 'product_id'),
                'attachment',
                'attachment.product_id',
                'product.id'
            )
            ->where('attachment.additional_purchase_id', $ids ?: [0])
            ->groupByJoins()
            ->all(Product::class)
            ->groupBy(fn ($item) => $item->additional_purchase_id);

        // Prepare Form
        $form = $this->formFactory->create(GridForm::class);
        $form->fill(compact('search', 'filter'));

        $showFilters = $this->showFilterBar($filter);

        $this->prepareMetadata($app, $view);

        return compact('items', 'pagination', 'form', 'showFilters', 'ordering', 'productSet');
    }

    public function prepareItem(Collection $item): object
    {
        return $this->repository->getEntityMapper()->toEntity($item);
    }

    /**
     * @template T
     *
     * @param  class-string<T>  $className
     * @param  mixed            $data
     *
     * @return  T
     *
     * @throws \ReflectionException
     */
    public function toEntity(string $className, mixed $data): object
    {
        return $this->orm->toEntity($className, $data);
    }

    /**
     * Get default ordering.
     *
     * @return  string
     */
    public function getDefaultOrdering(): string
    {
        return 'additional_purchase.ordering ASC';
    }

    /**
     * Get search fields.
     *
     * @return  string[]
     */
    public function getSearchFields(): array
    {
        return [
            'additional_purchase.id',
            'additional_purchase.title',
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
        return $ordering === 'additional_purchase.ordering ASC';
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
                $this->trans('unicorn.title.grid', title: $this->trans('shopgo.additional.purchase.title'))
            );
    }
}

<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Module\Admin\Order;

use Lyrasoft\ShopGo\Entity\Order;
use Lyrasoft\ShopGo\Module\Admin\Order\Form\GridForm;
use Lyrasoft\ShopGo\Repository\OrderRepository;
use Lyrasoft\ShopGo\Shipping\ShipmentCreatingInterface;
use Lyrasoft\ShopGo\Shipping\ShipmentPrintableInterface;
use Lyrasoft\ShopGo\Shipping\ShippingService;
use Lyrasoft\ShopGo\Traits\CurrencyAwareTrait;
use Unicorn\Selector\ListSelector;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Attributes\ViewModel;
use Windwalker\Core\Form\Exception\ValidateFailException;
use Windwalker\Core\Form\FormFactory;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\View\View;
use Windwalker\Core\View\ViewModelInterface;
use Windwalker\Data\Collection;
use Windwalker\DI\Attributes\Autowire;
use Windwalker\ORM\ORM;

use function Windwalker\response;

/**
 * The OrderListView class.
 */
#[ViewModel(
    layout: [
        'default' => 'order-list',
        'modal' => 'order-modal',
    ],
    js: 'order-list.js'
)]
class OrderListView implements ViewModelInterface
{
    use TranslatorTrait;
    use CurrencyAwareTrait;

    public function __construct(
        protected ORM $orm,
        #[Autowire]
        protected OrderRepository $repository,
        protected FormFactory $formFactory
    ) {
    }

    /**
     * Prepare view data.
     *
     * @param  AppContext  $app   The request app context.
     * @param  View        $view  The view object.
     *
     * @return  mixed
     */
    public function prepare(AppContext $app, View $view): mixed
    {
        $state = $this->repository->getState();

        // Prepare Items
        $page     = $state->rememberFromRequest('page');
        $limit    = $state->rememberFromRequest('limit');
        $filter   = (array) $state->rememberFromRequest('filter');
        $search   = (array) $state->rememberFromRequest('search');
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

        if ($app->input('task') === 'print_shipments') {
            return $this->printShipments($app, $items);
        }

        if ($app->input('task') === 'print_packaging') {
            return $this->printPackingList($app, $view, $items);
        }

        $pagination = $items->getPagination();

        // Prepare Form
        $form = $this->formFactory->create(GridForm::class);
        $form->fill(compact('search', 'filter'));

        $showFilters = $this->showFilterBar($filter);

        $this->prepareMetadata($app, $view);

        return compact('items', 'pagination', 'form', 'showFilters', 'ordering');
    }

    public function prepareItem(Collection $item): object
    {
        return $this->repository->getEntityMapper()->toEntity($item);
    }

    /**
     * Get default ordering.
     *
     * @return  string
     */
    public function getDefaultOrdering(): string
    {
        return 'order.id DESC';
    }

    /**
     * Get search fields.
     *
     * @return  string[]
     */
    public function getSearchFields(): array
    {
        return [
            'order.id',
            'order.no',
            'order.invoice_no',
            'order.payment_no',
            'order.shipping_no',
            'order.note',
            'order.search_index',
            'order.state_text',
            'user.name',
            'user.email',
            'user.username',
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
        return $ordering === 'order.ordering ASC';
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
                $this->trans('unicorn.title.grid', title: $this->trans('shopgo.order.title'))
            );
    }

    protected function printShipments(AppContext $app, ListSelector $items): mixed
    {
        $nav = $app->service(Navigator::class);
        $shippingService = $app->service(ShippingService::class);

        $items->page(1)->limit(0);

        $ids = (array) $app->input('id');

        if ($ids !== []) {
            $items->where('order.id', $ids);
        }

        $items->setDefaultItemClass(Order::class);
        $orders = [];
        $shipping = null;

        foreach ($items as $item) {
            $orders[] = $item;

            if (!$shipping) {
                $shipping = $item->getShipping();
            } elseif ($shipping->getType() !== $item->getShipping()?->getType()) {
                throw new ValidateFailException(
                    $this->trans(
                        'shopgo.order.message.print.shipment.should.be.same.shipping.type'
                    )
                );
            }
        }

        if (!$shipping) {
            return $nav->back();
        }

        $shippingInstance = $shippingService->createTypeInstance($shipping);

        if (!$shippingInstance) {
            throw new \RuntimeException(
                "Shipping type: `{$shipping->getType()}` not found."
            );
        }

        if ($shippingInstance instanceof ShipmentPrintableInterface) {
            $res = $shippingInstance->printShipments($app, $orders);

            if (!is_string($res)) {
                return $res;
            }

            if (trim($res) !== '') {
                return response()->html($res);
            }
        }

        return $nav->back();
    }

    public function printPackingList(AppContext $app, View $view, ListSelector $listSelector): mixed
    {
        $listSelector->page(1)->limit(0);

        $ids = (array) $app->input('id');

        if ($ids !== []) {
            $listSelector->where('order.id', $ids);
        }

        $orders = $listSelector->all(Order::class);

        $view->setLayout('packaging-list');

        return compact('orders');
    }
}

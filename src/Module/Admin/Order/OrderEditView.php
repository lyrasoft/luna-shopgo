<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Module\Admin\Order;

use Lyrasoft\ShopGo\Cart\Price\PriceObject;
use Lyrasoft\ShopGo\Cart\Price\PriceSet;
use Lyrasoft\ShopGo\Entity\Order;
use Lyrasoft\ShopGo\Entity\OrderHistory;
use Lyrasoft\ShopGo\Entity\OrderItem;
use Lyrasoft\ShopGo\Entity\OrderTotal;
use Lyrasoft\ShopGo\Module\Admin\Order\Form\EditForm;
use Lyrasoft\ShopGo\Repository\OrderRepository;
use Lyrasoft\ShopGo\Traits\CurrencyAwareTrait;
use Lyrasoft\Luna\Entity\User;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Attributes\ViewModel;
use Windwalker\Core\Form\FormFactory;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\View\View;
use Windwalker\Core\View\ViewModelInterface;
use Windwalker\Data\Collection;
use Windwalker\DI\Attributes\Autowire;
use Windwalker\ORM\ORM;

/**
 * The OrderEditView class.
 */
#[ViewModel(
    layout: 'order-edit',
    js: 'order-edit.js'
)]
class OrderEditView implements ViewModelInterface
{
    use TranslatorTrait;
    use CurrencyAwareTrait;

    public function __construct(
        protected ORM $orm,
        protected FormFactory $formFactory,
        protected Navigator $nav,
        #[Autowire] protected OrderRepository $repository
    ) {
    }

    /**
     * Prepare
     *
     * @param  AppContext  $app
     * @param  View        $view
     *
     * @return  mixed
     */
    public function prepare(AppContext $app, View $view): mixed
    {
        $id = $app->input('id');

        /** @var Order $item */
        $item = $this->repository->getItem($id);

        if (!$item) {
            return $this->nav->to('order_list');
        }

        $form = $this->formFactory
            ->create(EditForm::class)
            ->setNamespace('item')
            ->fill(
                $this->repository->getState()->getAndForget('edit.data')
                    ?: $this->orm->extractEntity($item)
            );

        // Totals
        $totalItems = $this->orm->mapper(OrderTotal::class)
            ->select()
            ->where('order_id', $item->getId())
            ->order('ordering', 'ASC')
            ->all(OrderTotal::class)
            ->map(
                function (OrderTotal $total) {
                    return new PriceObject(
                        $total->getCode(),
                        (string) $total->getValue(),
                        $total->getTitle(),
                        $total->getParams()
                    );
                }
            );

        $totals = new PriceSet();

        foreach ($totalItems as $totalItem) {
            $totals->set($totalItem);
        }

        $histories = $this->getOrderHistories($item);

        $orderItems = $this->orm->findList(
            OrderItem::class,
            [
                'order_id' => $item->getId(),
            ]
        )
            ->all();

        $this->prepareMetadata($app, $view);

        return compact('form', 'id', 'item', 'orderItems', 'totals', 'histories');
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
                $this->trans('unicorn.title.edit', title: $this->trans('shopgo.order.title'))
            );
    }

    public function getOrderHistories(Order $order): Collection
    {
        return $this->cacheStorage['histories.' . $order->getId()]
            ??= $this->orm
            ->from(OrderHistory::class)
            ->leftJoin(User::class, 'user', 'user.id', 'order_history.created_by')
            ->where('order_history.order_id', $order->getId())
            ->order('order_history.id', 'DESC')
            ->groupByJoins()
            ->all(OrderHistory::class);
    }
}

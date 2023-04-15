<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Module\Admin\Invoice;

use Lyrasoft\ShopGo\Cart\Price\PriceObject;
use Lyrasoft\ShopGo\Cart\Price\PriceSet;
use Lyrasoft\ShopGo\Entity\Order;
use Lyrasoft\ShopGo\Entity\OrderItem;
use Lyrasoft\ShopGo\Entity\OrderTotal;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Attributes\ViewModel;
use Windwalker\Core\View\View;
use Windwalker\Core\View\ViewModelInterface;
use Windwalker\ORM\ORM;
use Windwalker\Utilities\Attributes\Prop;

/**
 * The InvoiceView class.
 */
#[ViewModel(
    layout: 'invoice',
    js: 'invoice.js'
)]
class InvoiceView implements ViewModelInterface
{
    #[Prop]
    protected int $id;

    /**
     * Constructor.
     */
    public function __construct(protected ORM $orm)
    {
        //
    }

    /**
     * Prepare View.
     *
     * @param  AppContext  $app   The web app context.
     * @param  View        $view  The view object.
     *
     * @return  mixed
     * @throws \ReflectionException
     */
    public function prepare(AppContext $app, View $view): array
    {
        $id = $this->id;

        $order = $this->orm->mustFindOne(Order::class, $id);

        $orderItems = $this->orm->findList(
            OrderItem::class,
            [
                'order_id' => $order->getId(),
            ]
        )
            ->all();

        [$orderItems, $attachments] = $orderItems->partition(
            fn(OrderItem $orderItem) => $orderItem->getParentId() === 0
        );

        $attachments = $attachments->groupBy('parentId');

        // Totals
        $totalItems = $this->orm->mapper(OrderTotal::class)
            ->select()
            ->where('order_id', $order->getId())
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

        return compact('order', 'orderItems', 'attachments', 'totals');
    }
}

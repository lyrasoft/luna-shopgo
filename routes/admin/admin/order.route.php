<?php

declare(strict_types=1);

namespace App\Routes;

use Lyraoft\ShopGo\Module\Admin\Order\OrderController;
use Lyraoft\ShopGo\Module\Admin\Order\OrderEditView;
use Lyraoft\ShopGo\Module\Admin\Order\OrderListView;
use Windwalker\Core\Router\RouteCreator;

/** @var  RouteCreator $router */

$router->group('order')
    ->extra('menu', ['sidemenu' => 'order_list'])
    ->register(function (RouteCreator $router) {
        $router->any('order_list', '/order/list')
            ->controller(OrderController::class)
            ->view(OrderListView::class)
            ->postHandler('copy')
            ->putHandler('filter')
            ->patchHandler('batch');

        $router->any('order_edit', '/order/edit[/{id}]')
            ->controller(OrderController::class)
            ->view(OrderEditView::class);
    });

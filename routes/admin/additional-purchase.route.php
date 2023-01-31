<?php

declare(strict_types=1);

namespace App\Routes;

use App\Module\Admin\AdditionalPurchase\AdditionalPurchaseController;
use App\Module\Admin\AdditionalPurchase\AdditionalPurchaseEditView;
use App\Module\Admin\AdditionalPurchase\AdditionalPurchaseListView;
use Windwalker\Core\Router\RouteCreator;

/** @var  RouteCreator $router */

$router->group('additional-purchase')
    ->extra('menu', ['sidemenu' => 'additional_purchase_list'])
    ->register(function (RouteCreator $router) {
        $router->any('additional_purchase_list', '/additional-purchase/list')
            ->controller(AdditionalPurchaseController::class)
            ->view(AdditionalPurchaseListView::class)
            ->postHandler('copy')
            ->putHandler('filter')
            ->patchHandler('batch');

        $router->any('additional_purchase_edit', '/additional-purchase/edit[/{id}]')
            ->controller(AdditionalPurchaseController::class)
            ->view(AdditionalPurchaseEditView::class);
    });

<?php

declare(strict_types=1);

namespace App\Routes;

use Lyraoft\ShopGo\Module\Admin\Manufacturer\ManufacturerController;
use Lyraoft\ShopGo\Module\Admin\Manufacturer\ManufacturerEditView;
use Lyraoft\ShopGo\Module\Admin\Manufacturer\ManufacturerListView;
use Windwalker\Core\Router\RouteCreator;

/** @var  RouteCreator $router */

$router->group('manufacturer')
    ->extra('menu', ['sidemenu' => 'manufacturer_list'])
    ->register(function (RouteCreator $router) {
        $router->any('manufacturer_list', '/manufacturer/list')
            ->controller(ManufacturerController::class)
            ->view(ManufacturerListView::class)
            ->postHandler('copy')
            ->putHandler('filter')
            ->patchHandler('batch');

        $router->any('manufacturer_edit', '/manufacturer/edit[/{id}]')
            ->controller(ManufacturerController::class)
            ->view(ManufacturerEditView::class);
    });

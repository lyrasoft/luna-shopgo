<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Module\Admin\Order;

use App\Enum\OrderHistoryType;
use App\Enum\OrderState;
use App\Module\Admin\Order\Form\EditForm;
use App\Repository\OrderRepository;
use App\Service\OrderService;
use Unicorn\Controller\CrudController;
use Unicorn\Controller\GridController;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Attributes\Controller;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\RouteUri;
use Windwalker\DI\Attributes\Autowire;
use Windwalker\DI\Attributes\Service;

/**
 * The OrderController class.
 */
#[Controller()]
class OrderController
{
    public function save(
        AppContext $app,
        CrudController $controller,
        Navigator $nav,
        #[Autowire] OrderRepository $repository,
    ): mixed {
        $form = $app->make(EditForm::class);

        $uri = $app->call([$controller, 'save'], compact('repository', 'form'));

        switch ($app->input('task')) {
            case 'save2close':
                return $nav->to('order_list');

            case 'save2new':
                return $nav->to('order_edit')->var('new', 1);

            case 'save2copy':
                $controller->rememberForClone($app, $repository);
                return $nav->self($nav::WITHOUT_VARS)->var('new', 1);

            default:
                return $uri;
        }
    }

    public function delete(
        AppContext $app,
        #[Autowire] OrderRepository $repository,
        CrudController $controller
    ): mixed {
        return $app->call([$controller, 'delete'], compact('repository'));
    }

    public function filter(
        AppContext $app,
        #[Autowire] OrderRepository $repository,
        GridController $controller
    ): mixed {
        return $app->call([$controller, 'filter'], compact('repository'));
    }

    public function batch(
        AppContext $app,
        #[Autowire] OrderRepository $repository,
        GridController $controller
    ): mixed {
        $task = $app->input('task');

        if ($task === 'transition') {
            return $app->call([$this, 'transition']);
        }

        $data = match ($task) {
            'publish' => ['state' => 1],
            'unpublish' => ['state' => 0],
            default => null
        };

        return $app->call([$controller, 'batch'], compact('repository', 'data'));
    }

    public function transition(
        AppContext $app,
        #[Service]
        OrderService $orderService,
        Navigator $nav
    ): RouteUri {
        [$id, $stateId, $notify, $message] = $app->input('id', 'state', 'notify', 'message')
            ->values()
            ->dump();

        $orderService->transition(
            (int) $id,
            (int) $stateId,
            OrderHistoryType::ADMIN(),
            $message,
            (bool) $notify
        );

        return $nav->back();
    }

    public function copy(
        AppContext $app,
        #[Autowire] OrderRepository $repository,
        GridController $controller
    ): mixed {
        return $app->call([$controller, 'copy'], compact('repository'));
    }
}

<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Module\Admin\Order;

use App\Enum\OrderState;
use Lyrasoft\ShopGo\Entity\Order;
use Lyrasoft\ShopGo\Enum\OrderHistoryType;
use Lyrasoft\ShopGo\Module\Admin\Order\Form\EditForm;
use Lyrasoft\ShopGo\Repository\OrderRepository;
use Lyrasoft\ShopGo\Service\OrderService;
use Lyrasoft\ShopGo\Shipping\ShippingService;
use Unicorn\Controller\CrudController;
use Unicorn\Controller\GridController;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Attributes\Controller;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\RouteUri;
use Windwalker\DI\Attributes\Autowire;
use Windwalker\DI\Attributes\Service;
use Windwalker\ORM\ORM;

/**
 * The OrderController class.
 */
#[Controller()]
class OrderController
{
    use TranslatorTrait;

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

        if ($task === 'create_shipping') {
            return $app->call([$this, 'createShippingBills']);
        }

        if ($task === 'update_shipping') {
            return $app->call([$this, 'updateShippingStatus']);
        }

        if ($task === 'create_invoice') {
            return $app->call([$this, 'createInvoice']);
        }

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

    public function createShippingBills(
        AppContext $app,
        ORM $orm,
        Navigator $nav,
        ShippingService $shippingService
    ): RouteUri {
        $ids = (array) $app->input('id');

        foreach ($ids as $id) {
            $order = $orm->mustFindOne(Order::class, $id);

            $shippingInstance = $shippingService->createTypeInstance($order->getShipping());

            if (!$shippingInstance) {
                throw new \RuntimeException(
                    "Order: `{$order->getNote()}` shipping type: `{$order->getShipping()->getType()}` not found."
                );
            }

            $shippingInstance->createShippingBill($order);
        }

        $app->addMessage(
            $this->trans(
                'shopgo.order.message.create.shipping.bill.success',
                count: count($ids)
            ),
            'success'
        );

        return $nav->back();
    }

    public function updateShippingStatus(
        AppContext $app,
        ORM $orm,
        Navigator $nav,
        ShippingService $shippingService,
    ) {
        $ids = (array) $app->input('id');

        foreach ($ids as $id) {
            $order = $orm->mustFindOne(Order::class, $id);

            $shippingInstance = $shippingService->createTypeInstance($order->getShipping());

            if (!$shippingInstance) {
                throw new \RuntimeException(
                    "Order: `{$order->getNote()}` shipping type: `{$order->getShipping()->getType()}` not found."
                );
            }

            $shippingInstance->updateShippingStatus($order);
        }

        $app->addMessage(
            $this->trans(
                'shopgo.order.message.update.shipping.status.success',
                count: count($ids)
            ),
            'success'
        );

        return $nav->back();
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

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
use App\Service\ReceiptService;
use Lyrasoft\ShopGo\Entity\Order;
use Lyrasoft\ShopGo\Enum\OrderHistoryType;
use Lyrasoft\ShopGo\Module\Admin\Order\Form\EditForm;
use Lyrasoft\ShopGo\Repository\OrderRepository;
use Lyrasoft\ShopGo\Service\OrderService;
use Lyrasoft\ShopGo\Shipping\ShipmentCreatingInterface;
use Lyrasoft\ShopGo\Shipping\ShipmentPrintableInterface;
use Lyrasoft\ShopGo\Shipping\ShippingService;
use Lyrasoft\ShopGo\Shipping\ShippingStatusInterface;
use Unicorn\Controller\CrudController;
use Unicorn\Controller\GridController;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Attributes\Controller;
use Windwalker\Core\Form\Exception\ValidateFailException;
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

        if ($task === 'create_shipments') {
            return $app->call([$this, 'createShipments']);
        }

        if ($task === 'update_shippings') {
            return $app->call([$this, 'updateShippingStatuses']);
        }

        // if ($task === 'create_invoices') {
        //     return $app->call([$this, 'createInvoices']);
        // }

        if ($task === 'createReceipt') {
            return $app->call([$this, 'createReceipt']);
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

    public function createShipments(
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
                    "Order: `{$order->getNo()}` shipping type: `{$order->getShipping()->getType()}` not found."
                );
            }

            if ($shippingInstance instanceof ShipmentCreatingInterface) {
                $shippingInstance->createShipment($order);
            }
        }

        $app->addMessage(
            $this->trans(
                       'shopgo.order.message.create.shipment.success',
                count: count($ids)
            ),
            'success'
        );

        return $nav->back();
    }

    public function updateShippingStatuses(
        AppContext $app,
        ORM $orm,
        Navigator $nav,
        ShippingService $shippingService,
    ): RouteUri {
        $ids = (array) $app->input('id');

        foreach ($ids as $id) {
            $order = $orm->mustFindOne(Order::class, $id);

            $shippingInstance = $shippingService->createTypeInstance($order->getShipping());

            if (!$shippingInstance) {
                throw new \RuntimeException(
                    "Order: `{$order->getNo()}` shipping type: `{$order->getShipping()->getType()}` not found."
                );
            }

            if ($shippingInstance instanceof ShippingStatusInterface) {
                $shippingInstance->updateShippingStatus($order);
            }
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

    public function printShipments(
        AppContext $app,
        ORM $orm,
        Navigator $nav,
        ShippingService $shippingService,
    ): mixed {
        $ids = (array) $app->input('id');

        $orders = [];
        $shipping = null;

        foreach ($ids as $id) {
            $orders[] = $order = $orm->mustFindOne(Order::class, $id);

            if (!$shipping) {
                $shipping = $order->getShipping();
            } elseif ($shipping->getType() !== $order->getShipping()?->getType()) {
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
                "Order: `{$order->getNote()}` shipping type: `{$shipping->getType()}` not found."
            );
        }

        if ($shippingInstance instanceof ShipmentPrintableInterface) {
            return $shippingInstance->printShipments($app, $orders);
        }

        return $nav->back();
    }

    public function createReceipt(
        AppContext $app,
        #[Autowire] ReceiptService $receiptService,
        Navigator $nav,
        ORM $orm
    ): RouteUri {
        $ids = (array) $app->input('id');

        foreach ($ids as $id) {
            $receiptService->createReceipt((int) $id);
        }

        $app->addMessage('開立發票完成', 'success');

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
            ((int) $stateId) ?: null,
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

<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Module\Front\Order;

use Lyrasoft\Luna\User\UserService;
use Lyrasoft\ShopGo\Entity\Order;
use Lyrasoft\ShopGo\Enum\OrderHistoryType;
use Lyrasoft\ShopGo\Service\OrderHistoryService;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Attributes\Controller;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\RouteUri;
use Windwalker\Core\Security\Exception\UnauthorizedException;
use Windwalker\ORM\ORM;

/**
 * The OrderController class.
 */
#[Controller(
    config: __DIR__ . '/order.config.php'
)]
class OrderController
{
    public function save(
        AppContext $app,
        ORM $orm,
        UserService $userService,
        Navigator $nav,
        OrderHistoryService $orderHistoryService,
    ): RouteUri {
        $no = $app->input('no');

        $note = $app->input('item')['note'];

        $order = $orm->mustFindOne(Order::class, compact('no'));

        $user = $userService->getUser();

        if ($user->getId() !== $order->getUserId()) {
            throw new UnauthorizedException('Forbidden');
        }

        $orderHistoryService->createHistory(
            $order,
            null,
            OrderHistoryType::MEMBER(),
            $note
        );

        // Todo: notice admin

        return $nav->to('order_item')->var('no', $order->getNo());
    }
}

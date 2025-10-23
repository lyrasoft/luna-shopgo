<?php

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

        if ($user->id !== $order->userId) {
            throw new UnauthorizedException('Forbidden');
        }

        $history = $orderHistoryService->createHistory(
            $order,
            null,
            OrderHistoryType::MEMBER,
            $note
        );

        $orderHistoryService->notifyToAdmin($order, null, $history);

        return $nav->to('my_order_item')->var('no', $order->no);
    }
}

<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyraoft\ShopGo\Service;

use Lyraoft\ShopGo\Entity\Order;
use Lyraoft\ShopGo\Entity\OrderHistory;
use Lyraoft\ShopGo\Entity\OrderState;
use Lyraoft\ShopGo\Enum\OrderHistoryType;
use Lyraoft\ShopGo\Repository\OrderHistoryRepository;
use Windwalker\DI\Attributes\Autowire;

/**
 * The OrderHistoryService class.
 */
class OrderHistoryService
{
    public function __construct(
        #[Autowire]
        protected OrderHistoryRepository $repository
    ) {
    }

    public function createHistoryByOrderId(
        int $orderId,
        OrderState|null $state,
        OrderHistoryType $type,
        string $message = '',
        bool $notify = false
    ): OrderHistory {
        $history = new OrderHistory();
        $history->setType($type);
        $history->setCreatedBy(0);
        $history->setState($state);
        $history->setOrderId($orderId);
        $history->setMessage($message);
        $history->setNotify($notify);

        return $this->repository->save($history);
    }

    public function createHistory(
        Order $order,
        ?OrderState $state,
        OrderHistoryType $type,
        string $message = '',
        bool $notify = false
    ): OrderHistory {
        return $this->createHistoryByOrderId(
            $order->getId(),
            $state,
            $type,
            $message,
            $notify
        );
    }

    public function createHistoryAndNotify(
        Order $order,
        ?OrderState $state,
        OrderHistoryType $type,
        string $message = '',
        bool $notify = true
    ): OrderHistory {
        $history = $this->createHistoryByOrderId(
            $order->getId(),
            $state,
            $type,
            $message,
            $notify
        );

        if ($notify) {
            $this->notifyFor($order, $history);
        }

        return $history;
    }

    public function notifyFor(
        Order $order,
        OrderHistory $history,
    ): void {
        // Todo: Notify
    }
}

<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Service;

use Lyrasoft\ShopGo\Entity\Order;
use Lyrasoft\ShopGo\Entity\OrderHistory;
use Lyrasoft\ShopGo\Entity\OrderState;
use Lyrasoft\ShopGo\Enum\OrderHistoryType;
use Lyrasoft\ShopGo\Enum\OrderNoMode;
use Lyrasoft\ShopGo\ShopGoPackage;
use Lyrasoft\Sequence\Service\SequenceService;
use Lyrasoft\Toolkit\Encode\BaseConvert;
use Windwalker\Core\Application\ApplicationInterface;
use Windwalker\ORM\ORM;
use Windwalker\Utilities\Str;

use function Windwalker\now;

/**
 * The OrderService class.
 */
class OrderService
{
    public function __construct(
        protected ApplicationInterface $app,
        protected ORM $orm,
        protected ShopGoPackage $shopGo,
        protected OrderStateService $orderStateService,
        protected OrderHistoryService $orderHistoryService,
    ) {
    }

    public function transition(
        Order|int $order,
        OrderState|string|int $to,
        OrderHistoryType $type,
        string $message = '',
        bool $notify = false
    ): ?OrderHistory {
        if (!$order instanceof Order) {
            $order = $this->orm->mustFindOne(Order::class, $order);
        }

        if (is_int($to)) {
            $to = $this->orm->mustFindOne(OrderState::class, $to);
        } elseif (is_string($to)) {
            $to = $this->orm->mustFindOne(OrderState::class, ['alias' => $to]);
        }

        return $this->orm->getDb()->transaction(
            function () use ($notify, $message, $type, $to, $order) {
                $order = $this->orderStateService->changeState(
                    $order,
                    $to
                );

                return $this->orderHistoryService->createHistoryAndNotify(
                    $order,
                    $to,
                    $type,
                    $message,
                    $notify,
                );
            }
        );
    }

    public function createOrderNo(int $id): string
    {
        $prefix = (string) $this->shopGo->config('order_no.prefix');
        $mode = OrderNoMode::wrap(
            $this->shopGo->config('order_no.mode') ?: OrderNoMode::INCREMENT_ID()
        );

        if ($mode === OrderNoMode::INCREMENT_ID()) {
            $availableLength = $this->getAvailableNoLength($prefix);

            return $prefix . Str::padLeft((string) $id, $availableLength, '0');
        }

        if ($mode === OrderNoMode::DAILY_SEQUENCE()) {
            $sequenceService = $this->app->service(SequenceService::class);
            $format = $this->shopGo->config('order_no.sequence_day_format') ?: 'Ymd';
            $prefix .= now($format);

            $availableLength = $this->getAvailableNoLength($prefix);

            return $prefix . $sequenceService->getNextSerialAndPadZero('shopgo_order', $prefix, $availableLength);
        }

        if ($mode === OrderNoMode::SEQUENCE_HASHES()) {
            $offsets = (int) $this->shopGo->config('order_no.hash_offsets');
            $seed = $this->shopGo->config('order_no.hash_seed') ?: BaseConvert::BASE62;
            $hash = BaseConvert::encode($id + $offsets, $seed);

            return $prefix . $hash;
        }

        if ($mode === OrderNoMode::RANDOM_HASHES()) {
            $uid = bin2hex(random_bytes(6));
            $seed = $this->shopGo->config('order_no.hash_seed') ?: BaseConvert::BASE62;

            do {
                $no = $prefix . BaseConvert::encode(base_convert($uid, 16, 10), $seed);

                $exists = $this->orm->findOne(Order::class, ['no' => $no]);
            } while ($exists !== null);

            return $no;
        }

        throw new \RuntimeException('Order no config wrong');
    }

    public static function getCurrentTimeBase62(): string
    {
        $t = (string) time();

        return BaseConvert::encode($t, BaseConvert::BASE62);
    }

    public function getPaymentNo(string $orderNo, bool $test = false): string
    {
        if (!$test) {
            return $orderNo;
        }

        $t = static::getCurrentTimeBase62();

        return $orderNo . 'T' . $t;
    }

    /**
     * @param  string  $prefix
     *
     * @return  int
     */
    protected function getAvailableNoLength(string $prefix): int
    {
        $maxlength = (int) $this->shopGo->config('payment_no.maxlength') ?: 20;

        $t = static::getCurrentTimeBase62();

        $availableLength = $maxlength - strlen($t) - strlen($prefix) - 1;

        return min($availableLength, 11);
    }
}

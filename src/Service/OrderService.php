<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Service;

use App\Entity\Order;
use App\Enum\OrderNoMode;
use Lyrasoft\Sequence\Service\SequenceService;
use Lyrasoft\ShopGo\Config\ShopConfig;
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
        protected ShopConfig $shopConfig,
    ) {
    }

    public function createOrderNo(int $id): string
    {
        $prefix = (string) $this->shopConfig->get('order_no_prefix');
        $mode = OrderNoMode::wrap(
            $this->shopConfig->get('order_no_mode') ?: OrderNoMode::INCREMENT_ID()
        );

        if ($mode === OrderNoMode::INCREMENT_ID()) {
            $availableLength = $this->getAvailableNoLength($prefix);

            return $prefix . Str::padLeft((string) $id, $availableLength, '0');
        }

        if ($mode === OrderNoMode::DAILY_SEQUENCE()) {
            $sequenceService = $this->app->service(SequenceService::class);
            $format = $this->shopConfig->get('sequence_day_format') ?: 'Ymd';
            $prefix .= now($format);

            $availableLength = $this->getAvailableNoLength($prefix);

            return $prefix . $sequenceService->getNextSerialAndPadZero('order_no', (string) $id, $availableLength);
        }

        if ($mode === OrderNoMode::SEQUENCE_HASHES()) {
            $offsets = (int) $this->shopConfig->get('order_hash_offsets');
            $seed = $this->shopConfig->get('order_hash_seed') ?: BaseConvert::BASE62;
            $hash = BaseConvert::encode($id + $offsets, $seed);

            return $prefix . $hash;
        }

        if ($mode === OrderNoMode::RANDOM_HASHES()) {
            $uid = bin2hex(random_bytes(6));
            $seed = $this->shopConfig->get('order_hash_seed') ?: BaseConvert::BASE62;

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
        $maxlength = (int) $this->shopConfig->get('payment_no_maxlength') ?: 20;

        $t = static::getCurrentTimeBase62();

        $availableLength = $maxlength - strlen($t) - strlen($prefix) - 1;

        return min($availableLength, 11);
    }
}

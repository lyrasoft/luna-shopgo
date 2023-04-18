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
use Lyrasoft\ShopGo\Repository\OrderHistoryRepository;
use Lyrasoft\ShopGo\ShopGoPackage;
use Windwalker\Core\Application\ApplicationInterface;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Core\Mailer\MailerInterface;
use Windwalker\DI\Attributes\Autowire;

/**
 * The OrderHistoryService class.
 */
class OrderHistoryService
{
    use TranslatorTrait;

    protected ?\Closure $shouldNoticeAdminCallback = null;

    public function __construct(
        protected ApplicationInterface $app,
        #[Autowire]
        protected OrderHistoryRepository $repository,
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

        if ($notify && ($type === OrderHistoryType::SYSTEM() || $type === OrderHistoryType::ADMIN())) {
            $this->notifyToMember($order, $state, $history);
        }

        return $history;
    }

    public function notifyToMember(
        Order $order,
        ?OrderState $state,
        OrderHistory $history,
    ): void {
        $paymentData = $order->getPaymentData();
        $email = $paymentData->getEmail();

        if (!$email) {
            return;
        }

        $isAdmin = false;
        $shopGo = $this->app->service(ShopGoPackage::class);
        $sitename = $shopGo->config('shop.sitename') ?: 'ShopGo';

        $mailer = $this->app->service(MailerInterface::class);
        $message = $mailer->createMessage(
            $this->trans(
                'shopgo.order.mail.changed.notify.subject',
                sitename: $sitename,
                state: $order->getStateText(),
                no: $order->getNo()
            )
        )
            ->to($email)
            ->renderBody(
                'mail.order.order-changed',
                compact('order', 'history', 'state', 'isAdmin')
            );

        // Attach invoice
        if ($order->getPaidAt() && $state?->shouldAttachInvoice()) {
            $shopGo = $this->app->service(ShopGoPackage::class);
            $invoiceService = $this->app->service(InvoiceService::class);

            $message->attach(
                $invoiceService->createAndRenderInvoicePdf($order),
                sprintf(
                    '[%s] Invoice-%s.pdf',
                    $shopGo->config('shop.sitename') ?: 'ShopGo',
                    $order->getInvoiceNo()
                ),
                'application/pdf'
            );
        }

        $message->send();
    }

    public function notifyToAdmin(
        Order $order,
        OrderState|null $state,
        OrderHistory $history
    ): void {
        $mailNotifyService = $this->app->service(MailNotifyService::class);

        $users = $mailNotifyService->getAdminOrderNotifyReceivers();

        if (count($users)) {
            $isAdmin = true;
            $shopGo = $this->app->service(ShopGoPackage::class);
            $sitename = $shopGo->config('shop.sitename') ?: 'ShopGo';
            $emails = $users->column('email')->dump();

            $mailer = $this->app->service(MailerInterface::class);
            $mailer->createMessage(
                $this->trans(
                    'shopgo.order.mail.changed.notify.subject',
                    sitename: $sitename,
                    state: $order->getStateText(),
                    no: $order->getNo()
                )
            )
                ->bcc(...$emails)
                ->renderBody(
                    'mail.order.order-changed',
                    compact('order', 'history', 'state', 'isAdmin')
                )
                ->send();
        }
    }

    public function shouldNoticeAdmin(Order $order, OrderState|null $to): bool
    {
        if (!$to) {
            return false;
        }

        $callback = $this->getShouldNoticeAdminCallback();

        return $callback($order, $to);
    }

    /**
     * @return \Closure|null
     */
    public function getShouldNoticeAdminCallback(): ?\Closure
    {
        return $this->shouldNoticeAdminCallback ??= static function (Order $order, OrderState $state) {
            if ($state->isPaid() && !$order->getPaidAt()) {
                return true;
            }

            if ($state->isDone() && !$order->getDoneAt()) {
                return true;
            }

            if ($state->isCancel() && !$order->getCancelledAt()) {
                return true;
            }

            return false;
        };
    }

    /**
     * @param  \Closure|null  $shouldNoticeAdminCallback
     *
     * @return  static  Return self to support chaining.
     */
    public function shouldNoticeAdminCallback(?\Closure $shouldNoticeAdminCallback): static
    {
        $this->shouldNoticeAdminCallback = $shouldNoticeAdminCallback;

        return $this;
    }
}

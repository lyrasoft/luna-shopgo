<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Module\Front\Checkout;

use Lyrasoft\Luna\Entity\User;
use Lyrasoft\Luna\User\UserService;
use Lyrasoft\ShopGo\Cart\CartData;
use Lyrasoft\ShopGo\Cart\CartService;
use Lyrasoft\ShopGo\Cart\Contract\CheckoutProcessLayoutInterface;
use Lyrasoft\ShopGo\Entity\Order;
use Lyrasoft\ShopGo\Entity\Payment;
use Lyrasoft\ShopGo\Entity\Shipping;
use Lyrasoft\ShopGo\Enum\InvoiceType;
use Lyrasoft\ShopGo\Payment\PaymentService;
use Lyrasoft\ShopGo\Service\CheckoutService;
use Lyrasoft\ShopGo\Service\StockService;
use Lyrasoft\ShopGo\Shipping\ShippingService;
use Lyrasoft\ShopGo\ShopGoPackage;
use Psr\Http\Message\UriInterface;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Attributes\Controller;
use Windwalker\Core\Form\Exception\ValidateFailException;
use Windwalker\Core\Http\RequestAssert;
use Windwalker\Core\Manager\Logger;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\View\View;
use Windwalker\Http\Response\RedirectResponse;
use Windwalker\ORM\ORM;

use function Windwalker\response;

/**
 * The CheckoutController class.
 */
#[Controller(
    config: __DIR__ . '/checkout.config.php'
)]
class CheckoutController
{
    public function checkout(
        AppContext $app,
        ORM $orm,
        Navigator $nav,
        UserService $userService,
        ShopGoPackage $shopGo,
        StockService $stockService,
        CartService $cartService,
        CheckoutService $checkoutService
    ) {
        $checkout = (array) $app->input('checkout');

        $app->state->remember('checkout.data', $checkout);

        $allowAnonymous = $shopGo->config('checkout.allow_anonymous') ?? false;
        /** @var User $user */
        $user = $userService->getUser();

        if (!$allowAnonymous && !$user->isLogin()) {
            return $nav->to('cart');
        }

        /**
         * @var Order $order
         * @var CartData $cartData
         */
        [$order, $cartData] = $orm->getDb()->transaction(
            function () use ($checkout, $stockService, $cartService, $user, $checkoutService) {
                $order = new Order();

                $payment = (array) $checkout['payment'];
                $shipping = (array) $checkout['shipping'];

                $paymentData = (array) $checkout['payment_data'];
                $shippingData = (array) $checkout['shipping_data'];

                if ($shippingData['sync'] ?? false) {
                    $shippingData = $paymentData;
                }

                $addressId = $paymentData['address_id'] ?? null;

                $paymentLocation = $checkoutService->prepareAddressData(
                    (int) $addressId,
                    $paymentData,
                    $order->getPaymentData(),
                    $user
                );

                $shippingLocation = $checkoutService->prepareAddressData(
                    (int) $addressId,
                    $shippingData,
                    $order->getShippingData(),
                    $user
                );

                $cartData = $cartService->getCartDataForCheckout(
                    $shippingLocation->getId(),
                    $shipping['id'] ?? 0,
                    $payment['id'] ?? 0,
                    [],
                    true
                );

                $stockService->checkAndReduceStocks($cartData);

                $order->setUserId($user->getId());
                $order->setPaymentId((int) $payment['id']);
                $order->setShippingId((int) $shipping['id']);
                $order->setNote($checkout['note'] ?? '');

                if ($order->getPaymentData()->getVat()) {
                    $order->setInvoiceType(InvoiceType::COMPANY());
                } else {
                    $order->setInvoiceType(InvoiceType::IDV());
                }

                return [
                    $checkoutService->createOrder($order, $cartData, $checkout),
                    $cartData
                ];
            }
        );

        $checkoutService->notifyForCheckout($order, $cartData, $user);

        $completeUrl = $nav->to('checkout')
            ->layout('complete')
            ->var('no', $order->getNo())
            ->full();

        $res = $checkoutService->processPayment($order, $completeUrl);

        return $res ?? $completeUrl;
    }

    public function checkoutShipping(
        AppContext $app,
        ORM $orm,
        Navigator $nav,
        ShippingService $shippingService,
        CartService $cartService
    ) {
        $checkout = $app->input('checkout') ?? [];

        $shippingId = $checkout['shipping']['id'] ?? 0;

        if (!$shippingId) {
            throw new ValidateFailException('No shipping ID');
        }

        $shipping = $orm->mustFindOne(Shipping::class, $shippingId);
        $shippingInstance = $shippingService->createTypeInstance($shipping);

        if (!$shippingInstance instanceof CheckoutProcessLayoutInterface) {
            return response()->redirect($nav->to('checkout_payment'), 307);
        }

        $result = $shippingInstance->checkoutLayout($cartService->getCartData());

        if (is_string($result)) {
            /** @var View $view */
            $view = $app->make(CheckoutView::class);

            return $view->render(['content' => $result, 'data' => $checkout]);
        }

        if ($result instanceof UriInterface) {
            $result = new RedirectResponse($result, 307);
        }

        if ($result instanceof RedirectResponse) {
            $result = $result->withStatus(307);
        }

        return $result;
    }

    public function checkoutPayment(
        AppContext $app,
        ORM $orm,
        Navigator $nav,
        PaymentService $paymentService,
        CartService $cartService
    ) {
        $checkout = $app->input('checkout') ?? [];

        $paymentId = $checkout['payment']['id'] ?? 0;

        if (!$paymentId) {
            throw new ValidateFailException('No payment ID');
        }

        $payment = $orm->mustFindOne(Payment::class, $paymentId);
        $paymentInstance = $paymentService->createTypeInstance($payment);

        if (!$paymentInstance instanceof CheckoutProcessLayoutInterface) {
            return response()->redirect($nav->to('checkout'), 307);
        }

        $result = $paymentInstance->checkoutLayout($cartService->getCartData());

        if (is_string($result)) {
            /** @var View $view */
            $view = $app->make(CheckoutView::class);

            return $view->render(['content' => $result, 'data' => $checkout]);
        }

        if ($result instanceof UriInterface) {
            $result = new RedirectResponse($result, 307);
        }

        if ($result instanceof RedirectResponse) {
            $result = $result->withStatus(307);
        }

        return $result;
    }

    public function shippingTask(string $task, AppContext $app, ORM $orm, ShippingService $shippingService)
    {
        Logger::info('shipping-task', (string) $app->getSystemUri()->full());
        Logger::info('shipping-task', print_r($app->input()->dump(), true));

        $id = $app->input('id');

        RequestAssert::assert($id, 'No Shipping ID');

        $shipping = $orm->mustFindOne(Shipping::class, $id);

        return $shippingService->createTypeInstance($shipping)->runTask($app, $task);
    }

    public function paymentTask(string $task, AppContext $app, ORM $orm, PaymentService $paymentService)
    {
        Logger::info('payment-task', (string) $app->getSystemUri()->full());
        Logger::info('payment-task', print_r($app->input()->dump(), true));

        $id = $app->input('id');

        RequestAssert::assert($id, 'No Payment ID');

        $shipping = $orm->mustFindOne(Payment::class, $id);

        return $paymentService->createTypeInstance($shipping)->runTask($app, $task);
    }
}

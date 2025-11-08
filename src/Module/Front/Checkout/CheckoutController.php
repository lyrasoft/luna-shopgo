<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Module\Front\Checkout;

use Lyrasoft\Luna\Entity\User;
use Lyrasoft\Luna\User\UserService;
use Lyrasoft\ShopGo\Cart\CartData;
use Lyrasoft\ShopGo\Cart\CartService;
use Lyrasoft\ShopGo\Cart\CartStorage;
use Lyrasoft\ShopGo\Cart\Contract\CheckoutProcessLayoutInterface;
use Lyrasoft\ShopGo\Data\PaymentData;
use Lyrasoft\ShopGo\Data\ShippingData;
use Lyrasoft\ShopGo\Entity\Location;
use Lyrasoft\ShopGo\Entity\Order;
use Lyrasoft\ShopGo\Entity\Payment;
use Lyrasoft\ShopGo\Entity\Shipping;
use Lyrasoft\ShopGo\Enum\InvoiceType;
use Lyrasoft\ShopGo\Event\AfterCheckoutEvent;
use Lyrasoft\ShopGo\Event\BeforeCheckoutEvent;
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
use Windwalker\Http\HttpClient;
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
        $input = (array) $app->input('checkout');

        $app->state->remember('checkout.data', $input);

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
        [$order, $cartData] = $orm->transaction(
            function () use ($shopGo, $orm, $input, $stockService, $cartService, $user, $checkoutService) {
                $order = new Order();

                $payment = $input['payment'] ?? [];
                $shipping = $input['shipping'] ?? [];

                $paymentData = PaymentData::wrap($input['payment_data'] ?? []);
                $shippingData = ShippingData::wrap($input['shipping_data'] ?? []);

                // Emit Event use constructor
                $event = $shopGo->emit(
                    new BeforeCheckoutEvent(
                        order: $order,
                        payment: $payment,
                        shipping: $shipping,
                        paymentData: $paymentData,
                        shippingData: $shippingData,
                        input: $input,
                    )
                );

                $order = $event->order;
                $shipping = $event->shipping;
                $payment = $event->payment;
                $shippingData = $event->shippingData;
                $paymentData = $event->paymentData;
                $input = $event->input;

                if ($shippingData->sync) {
                    $shippingData->fillFrom($paymentData);
                }

                if (!$event->overridePaymentDataProcess) {
                    $addressId = $paymentData->addressId;

                    [$paymentData, $paymentLocation] = $checkoutService->prepareAddressData(
                        $addressId,
                        $paymentData,
                        $user
                    );

                    $order->paymentData = $paymentData;

                    if ($order->paymentData->vat) {
                        $order->invoiceType = InvoiceType::COMPANY;
                    } else {
                        $order->invoiceType = InvoiceType::IDV;
                    }
                }

                if (!$event->overrideShippingDataProcess) {
                    $addressId = $shippingData->addressId;

                    [$shippingData, $shippingLocation] = $checkoutService->prepareAddressData(
                        $addressId,
                        $shippingData,
                        $user
                    );

                    $order->shippingData = $shippingData;
                } else {
                    $shippingLocation = $orm->mustFindOne(
                        Location::class,
                        $order->shippingData->locationId
                    );
                }
                
                $cartData = $cartService->getCartDataForCheckout(
                    $shippingLocation->id,
                    $shipping['id'] ?? 0,
                    $payment['id'] ?? 0,
                    lock: true
                );

                $stockService->checkAndReduceStocks($cartData);

                $order->userId = $user->id;
                $order->paymentId = (string) $payment['id'];
                $order->shippingId = (string) $shipping['id'];
                $order->note = $input['note'] ?? '';

                return [
                    $checkoutService->createOrder($order, $cartData, $input),
                    $cartData
                ];
            }
        );

        $orderItems = $order->orderItems->getAttachedEntities();

        $event = $shopGo->emit(
            new AfterCheckoutEvent(
                order: $order,
                cartData: $cartData,
                orderItems: $orderItems,
                input: $input,
            )
        );

        $order = $event->order;
        $cartData = $event->cartData;

        $checkoutService->notifyForCheckout($order, $cartData);

        $completeUrl = $nav->to('checkout')
            ->layout('complete')
            ->var('no', $order->no)
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
        Logger::info('shipping-task', $uri = $app->getSystemUri()->full());
        Logger::info('shipping-task', print_r($app->input()->dump(), true));

        $http = new HttpClient();
        Logger::info(
            'shipping-task',
            $http->toCurlCmd('POST', $uri, HttpClient::formData($app->input()->dump()))
        );

        $id = $app->input('id');

        RequestAssert::assert($id, 'No Shipping ID');

        $shipping = $orm->mustFindOne(Shipping::class, $id);

        return $shippingService->createTypeInstance($shipping)->runTask($app, $task);
    }

    public function paymentTask(string $task, AppContext $app, ORM $orm, PaymentService $paymentService)
    {
        Logger::info('payment-task', $uri = $app->getSystemUri()->full());
        Logger::info('payment-task', print_r($app->input()->dump(), true));

        $http = new HttpClient();
        Logger::info(
            'payment-task',
            $http->toCurlCmd('POST', $uri, HttpClient::formData($app->input()->dump()))
        );

        $id = $app->input('id');

        RequestAssert::assert($id, 'No Payment ID');

        $shipping = $orm->mustFindOne(Payment::class, $id);

        return $paymentService->createTypeInstance($shipping)->runTask($app, $task);
    }
}

<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Seeder;

use App\Cart\CartItem;
use App\Cart\CartService;
use App\Entity\Address;
use App\Entity\Order;
use App\Entity\OrderHistory;
use App\Entity\OrderItem;
use App\Entity\OrderState;
use App\Entity\OrderTotal;
use App\Entity\Payment;
use App\Entity\Product;
use App\Entity\ProductVariant;
use App\Entity\Shipping;
use App\Enum\InvoiceType;
use App\Service\CheckoutService;
use App\Service\OrderStateService;
use Lyrasoft\Luna\Entity\User;
use Windwalker\Core\Seed\Seeder;
use Windwalker\Database\DatabaseAdapter;
use Windwalker\ORM\EntityMapper;
use Windwalker\ORM\ORM;

/**
 * Order Seeder
 *
 * @var Seeder          $seeder
 * @var ORM             $orm
 * @var DatabaseAdapter $db
 */
$seeder->import(
    static function (
        CheckoutService $checkoutService,
        CartService $cartService,
        OrderStateService $orderStateService
    ) use ($seeder, $orm, $db) {
        $faker = $seeder->faker('en_US');

        /** @var EntityMapper<Order> $mapper */
        $mapper = $orm->mapper(Order::class);

        $states = $orm->findList(OrderState::class)->all()->dump();
        $addresses = $orm->findList(Address::class)->all()->dump();
        $products = $orm->findList(Product::class)->all()->dump();
        $payments = $orm->findList(Payment::class)->all()->dump();
        $shippings = $orm->findList(Shipping::class)->all()->dump();
        $variantGroups = $orm->findList(ProductVariant::class)->all()->groupBy('productId');

        $users = $orm->findList(User::class)->all()->dump();

        foreach (range(1, 50) as $i) {
            /** @var User $user */
            $user = $faker->randomElement($users);

            /** @var Product[] $products */
            $chosenProducts = $faker->randomElements($products, random_int(3, 5));
            $productVariants = [];

            foreach ($chosenProducts as $product) {
                $variants = $variantGroups[$product->getId()] ?? [];

                if (count($variants) > 0) {
                    $variant = $faker->randomElement($variants);
                    $productVariants[] = $variant;
                }
            }

            $cartItems = [];

            /** @var ProductVariant $productVariant */
            foreach ($productVariants as $productVariant) {
                $cartItem = new CartItem();
                $cartItem->setVariant($productVariant)
                    ->setQuantity(random_int(1, 5))
                    ->setPriceSet($productVariant->getPriceSet())
                    ->setCover($productVariant->getCover())
                    ->setLink('#');

                $cartItems[] = $cartItem;
            }

            $cartData = $cartService->createCartDataFromItems($cartItems);

            $item = $mapper->createEntity();

            $item->setUserId($user->getId());

            /** @var Payment $payment */
            $payment = $faker->randomElement($payments);
            /** @var Address $paymentAddress */
            $paymentAddress = $faker->randomElement($addresses);

            $item->setPayment($payment->getId());

            $paymentData = $item->getPaymentData()
                ->setName($user->getName())
                ->setAddressId($paymentAddress->getId())
                ->setPhone($faker->phoneNumber())
                ->setMobile('09' . random_int(10000000, 99999999))
                ->setCompany($faker->company())
                ->setVat((string) random_int(10000000, 99999999));

            /** @var Shipping $shipping */
            $shipping = $faker->randomElement($shippings);
            /** @var Address $shippingAddress */
            $shippingAddress = $faker->randomElement($addresses);

            $item->setShipping($shipping->getId());

            $item->getShippingData()
                ->setName($faker->name())
                ->setAddressId($shippingAddress->getId())
                ->setPhone($faker->phoneNumber())
                ->setMobile('09' . random_int(10000000, 99999999))
                ->setNote($faker->sentence());

            // Invoice
            $item->setInvoiceType($faker->randomElement(InvoiceType::cases()));

            if ($item->getInvoiceType() === InvoiceType::COMPANY()) {
                $item->getInvoiceData()
                    ->setTitle($user->getName());
            } else {
                $item->getInvoiceData()
                    ->setTitle($paymentData->getCompany())
                    ->setVat($paymentData->getVat())
                    ->setMobile($paymentData->getMobile());
            }

            $order = $checkoutService->createOrder($item, $cartData, $user);

            /** @var OrderState $state */
            $state = $faker->randomElement($states);

            $order->setState($state);

            $orderStateService->mutateOrderByState(
                $order,
                $state,
                $faker->dateTimeBetween('-1years', 'now')
            );

            $orm->updateOne(Order::class, $order);

            $seeder->outCounting();
        }
    }
);

$seeder->clear(
    static function () use ($seeder, $orm, $db) {
        $seeder->truncate(Order::class, OrderItem::class, OrderTotal::class, OrderHistory::class);
    }
);

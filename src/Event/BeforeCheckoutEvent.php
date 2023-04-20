<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Event;

use Lyrasoft\ShopGo\Entity\Order;
use Windwalker\Event\AbstractEvent;

/**
 * The BeforeCheckoutEvent class.
 */
class BeforeCheckoutEvent extends AbstractEvent
{
    protected Order $order;

    protected array $payment = [];

    protected array $shipping = [];

    protected array $paymentData = [];

    protected array $shippingData = [];

    protected array $input = [];

    /**
     * @return Order
     */
    public function getOrder(): Order
    {
        return $this->order;
    }

    /**
     * @param  Order  $order
     *
     * @return  static  Return self to support chaining.
     */
    public function setOrder(Order $order): static
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return array
     */
    public function &getPayment(): array
    {
        return $this->payment;
    }

    /**
     * @param  array  $payment
     *
     * @return  static  Return self to support chaining.
     */
    public function setPayment(array $payment): static
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * @return array
     */
    public function &getShipping(): array
    {
        return $this->shipping;
    }

    /**
     * @param  array  $shipping
     *
     * @return  static  Return self to support chaining.
     */
    public function setShipping(array $shipping): static
    {
        $this->shipping = $shipping;

        return $this;
    }

    /**
     * @return array
     */
    public function &getPaymentData(): array
    {
        return $this->paymentData;
    }

    /**
     * @param  array  $paymentData
     *
     * @return  static  Return self to support chaining.
     */
    public function setPaymentData(array $paymentData): static
    {
        $this->paymentData = $paymentData;

        return $this;
    }

    /**
     * @return array
     */
    public function &getShippingData(): array
    {
        return $this->shippingData;
    }

    /**
     * @param  array  $shippingData
     *
     * @return  static  Return self to support chaining.
     */
    public function setShippingData(array $shippingData): static
    {
        $this->shippingData = $shippingData;

        return $this;
    }

    /**
     * @return array
     */
    public function &getInput(): array
    {
        return $this->input;
    }

    /**
     * @param  array  $input
     *
     * @return  static  Return self to support chaining.
     */
    public function setInput(array $input): static
    {
        $this->input = $input;

        return $this;
    }
}

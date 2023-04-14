<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Data;

use Lyrasoft\ShopGo\Data\Contract\AddressAwareInterface;
use Windwalker\Data\ValueObject;

/**
 * The PaymentData class.
 */
class PaymentData extends ValueObject implements AddressAwareInterface
{
    use AddressAwaitTrait;

    public string $paymentTitle = '';

    /**
     * @return string
     */
    public function getPaymentTitle(): string
    {
        return $this->paymentTitle;
    }

    /**
     * @param  string  $paymentTitle
     *
     * @return  static  Return self to support chaining.
     */
    public function setPaymentTitle(string $paymentTitle): static
    {
        $this->paymentTitle = $paymentTitle;

        return $this;
    }
}

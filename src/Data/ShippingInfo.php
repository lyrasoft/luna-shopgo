<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyraoft\ShopGo\Data;

use Windwalker\Core\DateTime\Chronos;
use Windwalker\Data\ValueObject;
use Windwalker\ORM\Attributes\CastNullable;

/**
 * The ShippingInfo class.
 */
class ShippingInfo extends ValueObject
{
    public string $shippingNo = '';

    public string $tradeNo = '';

    public string $type = '';

    public string $amount = '0';

    public string $targetName = '';

    public string $targetAddress = '';

    public bool $isCod = false;

    #[CastNullable(Chronos::class)]
    public ?Chronos $created;

    #[CastNullable(Chronos::class)]
    public ?Chronos $expired;

    /**
     * @return string
     */
    public function getShippingNo(): string
    {
        return $this->shippingNo;
    }

    /**
     * @param  string  $shippingNo
     *
     * @return  static  Return self to support chaining.
     */
    public function setShippingNo(string $shippingNo): static
    {
        $this->shippingNo = $shippingNo;

        return $this;
    }

    /**
     * @return string
     */
    public function getTradeNo(): string
    {
        return $this->tradeNo;
    }

    /**
     * @param  string  $tradeNo
     *
     * @return  static  Return self to support chaining.
     */
    public function setTradeNo(string $tradeNo): static
    {
        $this->tradeNo = $tradeNo;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param  string  $type
     *
     * @return  static  Return self to support chaining.
     */
    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    /**
     * @param  string  $amount
     *
     * @return  static  Return self to support chaining.
     */
    public function setAmount(string $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return string
     */
    public function getTargetName(): string
    {
        return $this->targetName;
    }

    /**
     * @param  string  $targetName
     *
     * @return  static  Return self to support chaining.
     */
    public function setTargetName(string $targetName): static
    {
        $this->targetName = $targetName;

        return $this;
    }

    /**
     * @return string
     */
    public function getTargetAddress(): string
    {
        return $this->targetAddress;
    }

    /**
     * @param  string  $targetAddress
     *
     * @return  static  Return self to support chaining.
     */
    public function setTargetAddress(string $targetAddress): static
    {
        $this->targetAddress = $targetAddress;

        return $this;
    }

    /**
     * @return bool
     */
    public function isCod(): bool
    {
        return $this->isCod;
    }

    /**
     * @param  bool  $isCod
     *
     * @return  static  Return self to support chaining.
     */
    public function setIsCod(bool $isCod): static
    {
        $this->isCod = $isCod;

        return $this;
    }

    /**
     * @return Chronos|null
     */
    public function getCreated(): ?Chronos
    {
        return $this->created;
    }

    /**
     * @param  Chronos|null  $created
     *
     * @return  static  Return self to support chaining.
     */
    public function setCreated(?Chronos $created): static
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return Chronos|null
     */
    public function getExpired(): ?Chronos
    {
        return $this->expired;
    }

    /**
     * @param  Chronos|null  $expired
     *
     * @return  static  Return self to support chaining.
     */
    public function setExpired(?Chronos $expired): static
    {
        $this->expired = $expired;

        return $this;
    }
}

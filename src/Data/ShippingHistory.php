<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Data;

use Windwalker\Core\DateTime\Chronos;
use Windwalker\Data\ValueObject;
use Windwalker\ORM\Attributes\CastNullable;

/**
 * The ShippingHistory class.
 */
class ShippingHistory extends ValueObject
{
    public string $statusCode = '';

    public string $statusText = '';

    public string $note = '';

    #[CastNullable(Chronos::class)]
    public ?Chronos $time;

    /**
     * @return string
     */
    public function getStatusCode(): string
    {
        return $this->statusCode;
    }

    /**
     * @param  string  $statusCode
     *
     * @return  static  Return self to support chaining.
     */
    public function setStatusCode(string $statusCode): static
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatusText(): string
    {
        return $this->statusText;
    }

    /**
     * @param  string  $statusText
     *
     * @return  static  Return self to support chaining.
     */
    public function setStatusText(string $statusText): static
    {
        $this->statusText = $statusText;

        return $this;
    }

    /**
     * @return string
     */
    public function getNote(): string
    {
        return $this->note;
    }

    /**
     * @param  string  $note
     *
     * @return  static  Return self to support chaining.
     */
    public function setNote(string $note): static
    {
        $this->note = $note;

        return $this;
    }

    /**
     * @return Chronos|null
     */
    public function getTime(): ?Chronos
    {
        return $this->time;
    }

    /**
     * @param  \DateTimeInterface|string|null  $time
     *
     * @return  static  Return self to support chaining.
     */
    public function setTime(\DateTimeInterface|string|null $time): static
    {
        $this->time = Chronos::wrapOrNull($time);

        return $this;
    }
}

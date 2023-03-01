<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Payment;

use Lyrasoft\ShopGo\Cart\CartData;
use Lyrasoft\ShopGo\Entity\Location;
use Lyrasoft\ShopGo\Entity\Order;
use Lyrasoft\ShopGo\Entity\Payment;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\RouteUri;
use Windwalker\Form\FieldDefinitionInterface;

/**
 * The AbstractPayment class.
 */
abstract class AbstractPayment implements FieldDefinitionInterface
{
    protected static string $type = '';

    protected Payment $data;

    abstract public static function getTypeIcon(): string;

    abstract public static function getTypeTitle(LangService $lang): string;

    abstract public static function getTypeDescription(LangService $lang): string;

    /**
     * @return string
     */
    public static function getType(): string
    {
        return static::$type;
    }

    /**
     * @param  string  $type
     *
     * @return  void
     */
    public static function setType(string $type): void
    {
        static::$type = $type;
    }

    abstract public function form(Location $location): string;

    abstract public function prepareOrder(Order $order, CartData $cartData, array $checkoutData = []): Order;

    abstract public function processCheckout(Order $order, RouteUri $completeUrl): mixed;

    abstract public function orderInfo(Order $order): string;

    abstract public function runTask(AppContext $app, string $task): mixed;

    abstract public function isTest(): bool;

    public function isSupported(CartData $cartData): bool
    {
        return true;
    }

    /**
     * @return Payment
     */
    public function getData(): Payment
    {
        return $this->data;
    }

    /**
     * @param  Payment  $data
     *
     * @return  static  Return self to support chaining.
     */
    public function setData(Payment $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function &getParams(): array
    {
        $params = &$this->getData()->getParams();

        return $params;
    }

    public function __call(string $name, array $args): mixed
    {
        return $this->getData()->$name(...$args);
    }

    public static function dir(): string
    {
        $ref = new \ReflectionClass(static::class);

        return dirname($ref->getFileName());
    }
}

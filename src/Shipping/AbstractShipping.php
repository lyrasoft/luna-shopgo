<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Shipping;

use Brick\Math\BigDecimal;
use Lyrasoft\ShopGo\Cart\CartData;
use Lyrasoft\ShopGo\Cart\Price\PriceObject;
use Lyrasoft\ShopGo\Entity\Location;
use Lyrasoft\ShopGo\Entity\Order;
use Lyrasoft\ShopGo\Entity\Shipping;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\RouteUri;
use Windwalker\Form\FieldDefinitionInterface;
use Windwalker\Form\Form;
use Windwalker\Utilities\Arr;
use Windwalker\Utilities\TypeCast;

/**
 * The AbstractShipping class.
 */
abstract class AbstractShipping implements FieldDefinitionInterface
{
    protected static string $type = '';

    protected Shipping $data;

    /**
     * Get type icon.
     *
     * @return  string
     */
    abstract public static function getTypeIcon(): string;

    /**
     * Get type title.
     *
     * @param  LangService  $lang
     *
     * @return  string
     */
    abstract public static function getTypeTitle(LangService $lang): string;

    /**
     * Get type description.
     *
     * @param  LangService  $lang
     *
     * @return  string
     */
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

    /**
     * Compute shipping fee for cart.
     *
     * @param  CartData     $cartData
     * @param  PriceObject  $total
     *
     * @return  BigDecimal
     */
    abstract public function computeShippingFee(CartData $cartData, PriceObject $total): BigDecimal;

    /**
     * The form at cart when selecting shippings.
     *
     * @param  Location  $location
     *
     * @return  string
     */
    abstract public function form(Location $location): string;

    /**
     * Prepare order data before create when checkout processing.
     *
     * @param  Order     $order
     * @param  CartData  $cartData
     * @param  array     $checkoutData
     *
     * @return  Order
     */
    abstract public function prepareOrder(Order $order, CartData $cartData, array $checkoutData = []): Order;

    /**
     * Return a uri or page response to handle shipping.
     *
     * Return NULL to skip this process.
     *
     * @param  Order     $order
     * @param  RouteUri  $notifyUrl
     *
     * @return  UriInterface|ResponseInterface|null
     */
    abstract public function processCheckout(Order $order, RouteUri $notifyUrl): UriInterface|ResponseInterface|null;

    /**
     * Show shipping info in order page. Can be HTML string.
     *
     * @param  Order  $order
     *
     * @return  string
     */
    abstract public function orderInfo(Order $order): string;

    /**
     * Run any task to support shipping provider's custom process.
     *
     * @param  AppContext  $app
     * @param  string      $task
     *
     * @return  mixed
     */
    abstract public function runTask(AppContext $app, string $task): mixed;

    /**
     * Get default form values for this shipping.
     *
     * @return  array
     *
     * @throws \ReflectionException
     */
    public function getDefaultFormValues(): array
    {
        $form = new Form();

        $form->defineFormFields($this);

        $data = [];

        $handleDefaults = static function (Form $form) use (&$data, &$handleDefaults) {
            foreach ($form->getFields() as $name => $field) {
                if (method_exists($field, 'getSubForm')) {
                    /** @var Form $form */
                    $subForm = $field->getSubForm();

                    $handleDefaults($subForm);
                } else {
                    $name = $field->getNamespaceName(true);

                    $value = Arr::get($data, $name, '/');

                    if (TypeCast::tryString($value) === '') {
                        $data = Arr::set($data, $name, $field->getDefaultValue(), '/');
                    }
                }
            }
        };

        $handleDefaults($form);

        return $data;
    }

    /**
     * Check that this shipping should show or not in shipping select list of cart.
     *
     * @param  CartData  $cartData
     *
     * @return  bool
     */
    public function isSupported(CartData $cartData): bool
    {
        return true;
    }

    /**
     * Get shipping entity data.
     *
     * @return Shipping
     */
    public function getData(): Shipping
    {
        return $this->data;
    }

    /**
     * @param  Shipping  $data
     *
     * @return  static  Return self to support chaining.
     */
    public function setData(Shipping $data): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get pricing matrix.
     *
     * @return  array
     */
    public function getPricing(): array
    {
        return $this->getData()->getPricing();
    }

    /**
     * Get params.
     *
     * @return  array
     */
    public function &getParams(): array
    {
        $params = &$this->getData()->getParams();

        return $params;
    }

    public function __call(string $name, array $args): mixed
    {
        return $this->getData()->$name(...$args);
    }

    /**
     * GEt dir of this shipping.
     *
     * @return  string
     */
    public static function dir(): string
    {
        $ref = new \ReflectionClass(static::class);

        return dirname($ref->getFileName());
    }

    public static function filePath(): string
    {
        return (new \ReflectionClass(static::class))->getFileName();
    }

    // public function computeAndAddShippingFee(ApplicationInterface $app, CartData $cartData, Location $location): void
    // {
    //     $fee = $this->computeShippingFee($app, $cartData, $location);
    // }
}

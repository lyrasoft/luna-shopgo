<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Shipping\Basic;

use Lyrasoft\ShopGo\Cart\CartData;
use Lyrasoft\ShopGo\Cart\Contract\CheckoutProcessLayoutInterface;
use Lyrasoft\ShopGo\Entity\Location;
use Lyrasoft\ShopGo\Entity\Order;
use Lyrasoft\ShopGo\Shipping\AbstractShipping;
use Lyrasoft\ShopGo\Shipping\PriceRangeTrait;
use Lyrasoft\ShopGo\Traits\CurrencyAwareTrait;
use Lyrasoft\ShopGo\Traits\LayoutAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Application\ApplicationInterface;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Core\Renderer\RendererService;
use Windwalker\Core\Router\RouteUri;
use Windwalker\DI\Attributes\Inject;
use Windwalker\Form\Field\TextField;
use Windwalker\Form\Form;
use Windwalker\Renderer\CompositeRenderer;

/**
 * The BasicShipping class.
 */
class BasicShipping extends AbstractShipping
{
    use TranslatorTrait;
    use CurrencyAwareTrait;
    use PriceRangeTrait;
    use LayoutAwareTrait;

    protected static string $type = '';

    public static function getTypeIcon(): string
    {
        return 'fa fa-truck';
    }

    public static function getTypeTitle(LangService $lang): string
    {
        return $lang('shopgo.shipping.basic.title');
    }

    public static function getTypeDescription(LangService $lang): string
    {
        return $lang('shopgo.shipping.basic.description');
    }

    public function define(Form $form): void
    {
        $form->ns(
            'params',
            fn (Form $form) => $form->fieldset('layout')
                ->title($this->trans('shopgo.shipping.fieldset.layout'))
                ->register(
                    function (Form $form) {
                        $form->add('checkout_form_layout', TextField::class)
                            ->label($this->trans('shopgo.shipping.field.checkout.form.layout'));
                    }
                )
        );

        $this->registerPricingForm($form);
    }

    public function form(Location $location): string
    {
        $layout = $this->getParams()['checkout_form_layout'] ?? '';

        if (!$layout) {
            return '';
        }

        return $this->renderLayout(
            $layout,
            [
                'shipping' => $this,
            ]
        );
    }

    public function prepareOrder(Order $order, CartData $cartData, array $checkoutData = []): Order
    {
        return $order;
    }

    public function processCheckout(Order $order, RouteUri $notifyUrl): UriInterface|ResponseInterface|null
    {
        return null;
    }

    public function orderInfo(Order $order): string
    {
        return '';
    }

    public function runTask(AppContext $app, string $task): mixed
    {
        return null;
    }
}

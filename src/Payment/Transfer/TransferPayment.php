<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Payment\Transfer;

use Lyrasoft\ShopGo\Cart\CartData;
use Lyrasoft\ShopGo\Entity\Location;
use Lyrasoft\ShopGo\Entity\Order;
use Lyrasoft\ShopGo\Payment\AbstractPayment;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Unicorn\Field\TinymceEditorField;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Core\Router\RouteUri;
use Windwalker\Form\Form;

/**
 * The TransferPayment class.
 */
class TransferPayment extends AbstractPayment
{
    use TranslatorTrait;

    public static function getTypeIcon(): string
    {
        return 'fa fa-money-bill-transfer';
    }

    public static function getTypeTitle(LangService $lang): string
    {
        return $lang('shopgo.payment.transfer.title');
    }

    public static function getTypeDescription(LangService $lang): string
    {
        return $lang('shopgo.payment.transfer.description');
    }

    public function define(Form $form): void
    {
        $form->ns('params', fn(Form $form) => $form->fieldset('info')
            ->title($this->trans('shopgo.payment.fieldset.info'))
            ->register(
                function (Form $form) {
                    $form->add('account_info', TinymceEditorField::class)
                        ->label($this->trans('shopgo.payment.field.account.info'))
                        ->editorOptions(
                            [
                                'height' => 400
                            ]
                        );
                }
            ));
    }

    public function form(Location $location): string
    {
        return '';
    }

    public function prepareOrder(Order $order, CartData $cartData, array $checkoutData = []): Order
    {
        return $order;
    }

    public function processCheckout(Order $order, RouteUri $completeUrl): mixed
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

    public function isTest(): bool
    {
        return false;
    }
}

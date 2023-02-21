<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Payment\Transfer;

use Lyrasoft\ShopGo\Payment\AbstractPayment;
use Unicorn\Field\TinymceEditorField;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Language\TranslatorTrait;
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
}

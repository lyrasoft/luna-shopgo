<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Module\Admin\Config\Form;

use App\Enum\OrderNoMode;
use App\Field\CurrencyListField;
use Unicorn\Field\ButtonRadioField;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Form\Field\HiddenField;
use Windwalker\Form\Field\ListField;
use Windwalker\Form\Field\NumberField;
use Windwalker\Form\Field\TextField;
use Windwalker\Form\FieldDefinitionInterface;
use Windwalker\Form\Form;

/**
 * The EditForm class.
 */
class ShopgoShopForm implements FieldDefinitionInterface
{
    use TranslatorTrait;

    /**
     * Define the form fields.
     *
     * @param  Form  $form  The Windwalker form object.
     *
     * @return  void
     */
    public function define(Form $form): void
    {
        $form->fieldset(
            'basic',
            function (Form $form) {
                $debug = WINDWALKER_DEBUG;

                $form->add('currency_main', CurrencyListField::class)
                    ->label($this->trans('shopgo.config.shop.field.main.currency'));

                $form->add('payment_no_maxlength', NumberField::class)
                    ->label($this->trans('shopgo.config.shop.field.payment.no.maxlength'))
                    ->defaultValue('20');

                $form->add('order_no_prefix', TextField::class)
                    ->label($this->trans('shopgo.config.shop.field.order.no.prefix'))
                    ->defaultValue('S')
                    ->disabled(!$debug);

                $form->add('invoice_no_prefix', TextField::class)
                    ->label($this->trans('shopgo.config.shop.field.invoice.no.prefix'))
                    ->defaultValue('INV')
                    ->disabled(!$debug);

                $form->add('order_no_mode', ListField::class)
                    ->label($this->trans('shopgo.config.shop.field.order.no.mode'))
                    ->registerFromEnums(OrderNoMode::class, $this->lang)
                    ->disabled(!$debug);

                $form->add('order_hash_offsets', NumberField::class)
                    ->label($this->trans('shopgo.config.shop.field.order.hash.offsets'))
                    ->disabled(!$debug);

                $form->add('order_hash_seed', TextField::class)
                    ->label($this->trans('shopgo.config.shop.field.order.hash.seed'))
                    ->disabled(!$debug);

                $form->add('sequence_day_format', TextField::class)
                    ->label($this->trans('shopgo.config.shop.field.sequence.day.format'))
                    ->disabled(!$debug);
            }
        );
    }
}

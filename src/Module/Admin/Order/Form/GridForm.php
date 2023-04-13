<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Module\Admin\Order\Form;

use Lyrasoft\ShopGo\Field\OrderStateListField;
use Lyrasoft\ShopGo\Field\PaymentModalField;
use Lyrasoft\ShopGo\Field\ShippingModalField;
use Unicorn\Field\CalendarField;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Form\Field\SearchField;
use Windwalker\Form\FieldDefinitionInterface;
use Windwalker\Form\Form;

/**
 * The GridForm class.
 */
class GridForm implements FieldDefinitionInterface
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
        $form->ns(
            'search',
            function (Form $form) {
                $form->add('*', SearchField::class)
                    ->label($this->trans('unicorn.grid.search.label'))
                    ->placeholder($this->trans('unicorn.grid.search.label'))
                    ->onchange('this.form.submit()');
            }
        );

        $form->ns(
            'filter',
            function (Form $form) {
                $form->add('order.state', OrderStateListField::class)
                    ->label($this->trans('unicorn.field.state'))
                    ->option($this->trans('unicorn.select.placeholder'), '')
                    ->onchange('this.form.submit()');

                $form->add('order.shipping_id', ShippingModalField::class)
                    ->label($this->trans('shopgo.order.filter.shipping'))
                    ->option($this->trans('unicorn.select.placeholder'), '')
                    ->onchange('this.form.submit()');

                $form->add('order.payment_id', PaymentModalField::class)
                    ->label($this->trans('shopgo.order.filter.payment'))
                    ->option($this->trans('unicorn.select.placeholder'), '')
                    ->onchange('this.form.submit()');

                $form->add('start_date', CalendarField::class)
                    ->label($this->trans('shopgo.order.filter.start.date'))
                    ->onchange('this.form.submit()');

                $form->add('end_date', CalendarField::class)
                    ->label($this->trans('shopgo.order.filter.end.date'))
                    ->onchange('this.form.submit()');
            }
        );

        $form->ns(
            'batch',
            function (Form $form) {
                //
            }
        );
    }
}

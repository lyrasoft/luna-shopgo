<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Module\Admin\OrderState\Form;

use Unicorn\Field\ColorPickerField;
use Unicorn\Field\SingleImageDragField;
use Unicorn\Field\SwitcherField;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Form\Field\HiddenField;
use Windwalker\Form\Field\NumberField;
use Windwalker\Form\Field\TextField;
use Windwalker\Form\FieldDefinitionInterface;
use Windwalker\Form\Form;

/**
 * The EditForm class.
 */
class EditForm implements FieldDefinitionInterface
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
        $form->add('title', TextField::class)
            ->label('Title')
            ->addFilter('trim');

        $form->fieldset(
            'basic',
            function (Form $form) {
                $form->add('default', SwitcherField::class)
                    ->label($this->trans('shopgo.order.state.field.default'))
                    ->circle(true)
                    ->color('primary');

                $form->add('color', ColorPickerField::class)
                    ->label($this->trans('shopgo.order.state.field.color'));

                $form->add('notice', SwitcherField::class)
                    ->label($this->trans('shopgo.order.state.field.notice'))
                    ->circle(true)
                    ->color('primary');

                $form->add('attach_invoice', SwitcherField::class)
                    ->label($this->trans('shopgo.order.state.field.attach_invoice'))
                    ->circle(true)
                    ->color('primary');

                $form->add('shipped', SwitcherField::class)
                    ->label($this->trans('shopgo.order.state.field.shipped'))
                    ->circle(true)
                    ->color('primary');

                $form->add('paid', SwitcherField::class)
                    ->label($this->trans('shopgo.order.state.field.paid'))
                    ->circle(true)
                    ->color('primary');

                $form->add('returned', SwitcherField::class)
                    ->label($this->trans('shopgo.order.state.field.returned'))
                    ->circle(true)
                    ->color('primary');

                $form->add('done', SwitcherField::class)
                    ->label($this->trans('shopgo.order.state.field.done'))
                    ->circle(true)
                    ->color('primary');

                $form->add('cancel', SwitcherField::class)
                    ->label($this->trans('shopgo.order.state.field.cancel'))
                    ->circle(true)
                    ->color('primary');

                $form->add('rollback', SwitcherField::class)
                    ->label($this->trans('shopgo.order.state.field.rollback'))
                    ->circle(true)
                    ->color('primary');
            }
        );
        $form->add('id', HiddenField::class);
    }
}

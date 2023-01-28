<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Module\Admin\Discount\Form;

use Lyrasoft\Luna\Field\UserModalField;
use Unicorn\Field\ButtonRadioField;
use Unicorn\Field\SwitcherField;
use Windwalker\Form\Field\TextareaField;
use Unicorn\Field\CalendarField;
use Windwalker\Form\Field\NumberField;
use Windwalker\Form\Field\HiddenField;
use Unicorn\Enum\BasicState;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Form\Field\ListField;
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
            ->label($this->trans('unicorn.field.title'))
            ->addFilter('trim')
            ->required(true);

        $form->add('alias', TextField::class)
            ->label($this->trans('unicorn.field.alias'))
            ->addFilter('trim');

        $form->fieldset(
            'basic',
            function (Form $form) {
                $form->add('subtype', ListField::class)
                    ->label($this->trans('shopgo.discount.field.subtype'))
                    ->option($this->trans('shopgo.discount.subtype.basic'), 'basic')
                    ->option($this->trans('shopgo.discount.subtype.code'), 'code');

                $text = $this->trans('shopgo.discount.button.gen.code');

                $form->add('code', TextField::class)
                    ->label($this->trans('shopgo.discount.field.code'))
                    ->set(
                        'append',
                        <<<HTML
                        <button type="button" class="btn btn-primary" data-task="gencode">
                            $text
                        </button>
                        HTML
                    );

                $form->add('notice', TextField::class)
                    ->label($this->trans('shopgo.discount.field.notice'));

                $form->add('description', TextareaField::class)
                    ->label($this->trans('unicorn.field.description'))
                    ->rows(10);
            }
        );

        $form->fieldset(
            'conditions',
            function (Form $form) {
                $form->add('quantity', NumberField::class)
                    ->label($this->trans('shopgo.discount.field.quantity'));

                $form->add('min_price', NumberField::class)
                    ->label($this->trans('shopgo.discount.field.min_price'))
                    ->step('0.0001');

                $form->add('min_cart_items', NumberField::class)
                    ->label($this->trans('shopgo.discount.field.min_cart_items'));

                $form->add('min_cart_price', NumberField::class)
                    ->label($this->trans('shopgo.discount.field.min_cart_price'))
                    ->step('0.0001');

                $form->add('times_per_user', NumberField::class)
                    ->label($this->trans('shopgo.discount.field.times_per_user'));

                $form->add('first_buy', NumberField::class)
                    ->label($this->trans('shopgo.discount.field.first_buy'));

                $form->add('after_registered', NumberField::class)
                    ->label($this->trans('shopgo.discount.field.after_registered'));

                $form->add('can_rollback', NumberField::class)
                    ->label($this->trans('shopgo.discount.field.can_rollback'));
            }
        );

        $form->fieldset(
            'specific',
            function (Form $form) {
                $form->add('categories', TextareaField::class)
                    ->label($this->trans('shopgo.discount.field.categories'))
                    ->rows(7);

                $form->add('prodcuts', TextareaField::class)
                    ->label($this->trans('shopgo.discount.field.prodcuts'))
                    ->rows(7);

                $form->add('payments', TextareaField::class)
                    ->label($this->trans('shopgo.discount.field.payments'))
                    ->rows(7);

                $form->add('shippings', TextareaField::class)
                    ->label($this->trans('shopgo.discount.field.shippings'))
                    ->rows(7);
            }
        );

        $form->fieldset(
            'combine',
            function (Form $form) {

                $form->add('combine', TextField::class)
                    ->label($this->trans('shopgo.discount.field.combine'));

                $form->add('combine_targets', TextareaField::class)
                    ->label($this->trans('shopgo.discount.field.combine_targets'))
                    ->rows(7);
            }
        );

        $form->fieldset(
            'pricing',
            function (Form $form) {
                $form->add('price', NumberField::class)
                    ->label($this->trans('shopgo.discount.field.price'))
                    ->step('0.0001');

                $form->add('apply_products', TextareaField::class)
                    ->label($this->trans('shopgo.discount.field.apply_products'))
                    ->rows(7);

                $form->add('free_shipping', NumberField::class)
                    ->label($this->trans('shopgo.discount.field.free_shipping'));

                $form->add('method', TextField::class)
                    ->label($this->trans('shopgo.discount.field.method'));

                $form->add('apply_to', TextField::class)
                    ->label($this->trans('shopgo.discount.field.apply_to'));
            }
        );

        $form->fieldset(
            'meta',
            function (Form $form) {
                $form->add('state', SwitcherField::class)
                    ->label($this->trans('unicorn.field.published'))
                    ->circle(true)
                    ->color('success')
                    ->defaultValue('1');

                $form->add('hide', SwitcherField::class)
                    ->label($this->trans('shopgo.discount.field.hide'))
                    ->circle(true)
                    ->color('warning')
                    ->defaultValue('1');

                $form->add('publish_up', CalendarField::class)
                    ->label($this->trans('shopgo.discount.field.publish_up'));

                $form->add('publish_down', CalendarField::class)
                    ->label($this->trans('shopgo.discount.field.publish_down'));

                $form->add('created', CalendarField::class)
                    ->label($this->trans('unicorn.field.created'))
                    ->disabled(true);

                $form->add('modified', CalendarField::class)
                    ->label($this->trans('unicorn.field.modified'))
                    ->disabled(true);

                $form->add('created_by', UserModalField::class)
                    ->label($this->trans('unicorn.field.author'))
                    ->disabled(true);

                $form->add('modified_by', UserModalField::class)
                    ->label($this->trans('unicorn.field.modified_by'))
                    ->disabled(true);

                $form->add('type', HiddenField::class);
            }
        );

        $form->add('id', HiddenField::class);
    }
}

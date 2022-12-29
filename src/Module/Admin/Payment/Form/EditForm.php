<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Module\Admin\Payment\Form;

use App\Field\LocationModalField;
use App\Field\OrderStateListField;
use Lyrasoft\Luna\Field\CategoryModalField;
use Windwalker\Core\Language\TranslatorTrait;
use Lyrasoft\Luna\Field\UserModalField;
use Unicorn\Field\CalendarField;
use Unicorn\Field\SwitcherField;
use Unicorn\Field\SingleImageDragField;
use Windwalker\Form\Field\TextareaField;
use Windwalker\Form\Field\NumberField;
use Windwalker\Form\Field\HiddenField;
use Unicorn\Enum\BasicState;
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
            ->label('Title')
            ->addFilter('trim');

        $form->fieldset(
            'basic',
            function (Form $form) {
                $form->add('description', TextareaField::class)
                    ->label($this->trans('unicorn.field.description'))
                    ->rows(4);

                $form->add('order_state_id', OrderStateListField::class)
                    ->label($this->trans('shopgo.payment.field.order.state'));

                $form->add('location_category_id', CategoryModalField::class)
                    ->label($this->trans('shopgo.payment.field.location_category'))
                    ->categoryType('location');

                $form->add('location_id', LocationModalField::class)
                    ->label($this->trans('shopgo.payment.field.location'));
            }
        );

        $form->fieldset(
            'meta',
            function (Form $form) {
                $form->add('image', SingleImageDragField::class)
                    ->label($this->trans('unicorn.field.image'))
                    ->crop(true)
                    ->width(400)
                    ->height(400);

                $form->add('state', SwitcherField::class)
                    ->label($this->trans('unicorn.field.published'))
                    ->circle(true)
                    ->color('success')
                    ->defaultValue('1');

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
            }
        );

        $form->add('id', HiddenField::class);
    }
}

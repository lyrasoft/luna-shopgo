<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Module\Admin\Shipping\Form;

use Lyrasoft\Luna\Field\TagListField;
use Lyrasoft\ShopGo\Field\LocationModalField;
use Lyrasoft\ShopGo\Field\PaymentModalField;
use Lyrasoft\Luna\Field\CategoryModalField;
use Lyrasoft\Luna\Field\UserModalField;
use Unicorn\Field\CalendarField;
use Unicorn\Field\SingleImageDragField;
use Unicorn\Field\SwitcherField;
use Unicorn\Field\TinymceEditorField;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Form\Field\HiddenField;
use Windwalker\Form\Field\TextareaField;
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

        $form->fieldset('basic')
            ->title($this->trans('unicorn.fieldset.basic'))
            ->register(
                function (Form $form) {
                    $form->add('subtitle', TextField::class)
                        ->label($this->trans('shopgo.shipping.field.subtitle'));

                    $form->add('description', TinymceEditorField::class)
                        ->label($this->trans('unicorn.field.description'))
                        ->editorOptions(
                            [
                                'height' => 400
                            ]
                        )
                        ->rows(4);

                    $form->add('location_category_id', CategoryModalField::class)
                        ->label($this->trans('shopgo.payment.field.location_category'))
                        ->categoryType('location');

                    $form->add('location_id', LocationModalField::class)
                        ->label($this->trans('shopgo.payment.field.location'));

                    $form->add('tags', TagListField::class)
                        ->label($this->trans('shopgo.shipping.field.tags'))
                        ->addClass('has-tom-select')
                        ->multiple(true);

                    $form->add('payments', PaymentModalField::class)
                        ->label($this->trans('shopgo.shipping.field.payments'))
                        ->multiple(true);
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
                    ->defaultValue('0');

                $form->add('note', TextField::class)
                    ->label($this->trans('shopgo.shipping.field.note'))
                    ->maxlength(255);

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

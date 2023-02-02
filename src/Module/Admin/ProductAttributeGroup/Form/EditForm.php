<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Module\Admin\ProductAttributeGroup\Form;

use Lyrasoft\Luna\Field\UserModalField;
use Unicorn\Field\CalendarField;
use Unicorn\Field\CategoryFlatListField;
use Unicorn\Field\SwitcherField;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Form\Field\HiddenField;
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
        // ID
        $form->add('id', HiddenField::class);

        // Title
        $form->add('title', TextField::class)
            ->label($this->trans('unicorn.field.title'))
            ->placeholder($this->trans('unicorn.field.title'))
            ->addFilter('trim')
            ->required(true);

        // Basic fieldset
        $form->fieldset(
            'basic',
            function (Form $form) {
                $form->add('categories', CategoryFlatListField::class)
                    ->label($this->trans('shopgo.product.attribute.group.field.category'))
                    ->categoryType('product')
                    ->multiple(true);
            }
        );

        // Meta fieldset
        $form->fieldset(
            'meta',
            function (Form $form) {
                // State
                $form->add('state', SwitcherField::class)
                    ->label($this->trans('unicorn.field.published'))
                    ->addClass('')
                    ->circle(true)
                    ->color('success')
                    ->defaultValue(1);

                // Created
                $form->add('created', CalendarField::class)
                    ->label($this->trans('unicorn.field.created'));

                // Modified
                $form->add('modified', CalendarField::class)
                    ->label($this->trans('unicorn.field.modified'))
                    ->disabled(true);

                // Author
                $form->add('created_by', UserModalField::class)
                    ->label($this->trans('unicorn.field.author'));

                // Modified User
                $form->add('modified_by', UserModalField::class)
                    ->label($this->trans('unicorn.field.modified.by'))
                    ->disabled(true);
            }
        );

        // if ($this->isLocaleEnabled()) {
        //     $form->add('language', LocaleSwitchField::class)
        //         ->label($this->trans('luna.field.language'))
        //         ->table(Category::class)
        //         ->required(true)
        //         ->allowCreateEmpty(true);
        // }
    }
}

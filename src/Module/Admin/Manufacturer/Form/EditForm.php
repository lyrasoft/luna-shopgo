<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyraoft\ShopGo\Module\Admin\Manufacturer\Form;

use Lyraoft\ShopGo\Entity\Manufacturer;
use Lyrasoft\Luna\Field\LocaleSwitchField;
use Lyrasoft\Luna\Field\TagListField;
use Lyrasoft\Luna\Field\UserModalField;
use Lyrasoft\Luna\Locale\LocaleAwareTrait;
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
    use LocaleAwareTrait;

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

        $form->add('alias', TextField::class)
            ->label('Alias')
            ->addFilter('trim');

        $form->fieldset(
            'basic',
            function (Form $form) {
                $form->add('image', SingleImageDragField::class)
                    ->label($this->trans('unicorn.field.image'));

                $form->add('introtext', TinymceEditorField::class)
                    ->label($this->trans('shopgo.manufacturer.field.introtext'))
                    ->editorOptions(
                        [
                            'height' => 400,
                        ]
                    );
            }
        );

        $form->fieldset(
            'seo',
            function (Form $form) {
                $form->add('meta_title', TextField::class)
                    ->label($this->trans('shopgo.manufacturer.field.meta.title'));

                $form->add('meta_description', TextareaField::class)
                    ->label($this->trans('shopgo.manufacturer.field.meta.description'))
                    ->rows(4);

                $form->add('meta_keywords', TextField::class)
                    ->label($this->trans('shopgo.manufacturer.field.meta.keywords'));
            }
        );

        $form->fieldset(
            'meta',
            function (Form $form) {
                $form->add('tags', TagListField::class)
                    ->label($this->trans('shopgo.manufacturer.field.tags'))
                    ->multiple(true);

                $form->add('state', SwitcherField::class)
                    ->label($this->trans('unicorn.field.published'))
                    ->circle(true)
                    ->color('success');

                $form->add('created', CalendarField::class)
                    ->label($this->trans('unicorn.field.created'));

                $form->add('modified', CalendarField::class)
                    ->label($this->trans('unicorn.field.modified'))
                    ->disabled(true);

                $form->add('created_by', UserModalField::class)
                    ->label($this->trans('unicorn.field.author'));

                $form->add('modified_by', UserModalField::class)
                    ->label($this->trans('unicorn.field.modified_by'))
                    ->disabled(true);
            }
        );

        $form->add('id', HiddenField::class);

        if ($this->isLocaleEnabled()) {
            $form->add('language', LocaleSwitchField::class)
                ->label($this->trans('luna.field.language'))
                ->table(Manufacturer::class)
                ->required(true)
                ->allowCreateEmpty(true);
        }
    }
}

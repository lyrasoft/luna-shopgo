<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Module\Admin\Product\Form;

use Lyrasoft\ShopGo\Field\ShippingModalField;
use Lyrasoft\Luna\Field\CategoryListField;
use Lyrasoft\Luna\Field\CategoryModalField;
use Lyrasoft\Luna\Field\UserModalField;
use Unicorn\Field\CalendarField;
use Unicorn\Field\InlineField;
use Unicorn\Field\MultiUploaderField;
use Unicorn\Field\SingleImageDragField;
use Unicorn\Field\SwitcherField;
use Unicorn\Field\TinymceEditorField;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Form\Field\HiddenField;
use Windwalker\Form\Field\NumberField;
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

        $form->fieldset(
            'info1',
            function (Form $form) {
                $form->add('variant/price', NumberField::class)
                    ->label($this->trans('shopgo.product.field.price'))
                    ->step('0.0001')
                    ->min(0)
                    ->required(true);

                $form->add('origin_price', NumberField::class)
                    ->label($this->trans('shopgo.product.field.origin_price'))
                    ->step('0.0001')
                    ->min(0);

                $form->add('variant/stock_quantity', NumberField::class)
                    ->label($this->trans('shopgo.product.field.stock_quantity'))
                    ->min(0)
                    ->required(true);

                $form->add('variant/subtract', SwitcherField::class)
                    ->label($this->trans('shopgo.product.field.subtract'))
                    ->circle(true)
                    ->color('primary')
                    ->defaultValue('1');

                $form->add('safe_stock', NumberField::class)
                    ->label($this->trans('shopgo.product.field.safe_stock'))
                    ->min(0);

                $form->add('out_of_stock_text', TextField::class)
                    ->label($this->trans('shopgo.product.field.stock_text'));
            }
        );

        $form->fieldset(
            'info2',
            function (Form $form) {
                $form->add('can_attach', SwitcherField::class)
                    ->label($this->trans('shopgo.product.field.can_attach'))
                    ->circle(true)
                    ->color('success')
                    ->defaultValue('1');

                $form->add('manufacturer_id', NumberField::class)
                    ->label($this->trans('shopgo.product.field.manufacturer'));

                $form->add('variant/dimension', InlineField::class)
                    ->label($this->trans('shopgo.product.field.dimension'))
                    ->showLabel(true)
                    ->asGroup(true)
                    ->configureForm(
                        function (Form $form) {
                            $form->add('length', NumberField::class)
                                ->label($this->trans('shopgo.product.field.length'))
                                ->step('0.0001')
                                ->set('floating', true);

                            $form->add('width', NumberField::class)
                                ->label($this->trans('shopgo.product.field.width'))
                                ->step('0.0001')
                                ->set('floating', true);

                            $form->add('height', NumberField::class)
                                ->label($this->trans('shopgo.product.field.height'))
                                ->step('0.0001')
                                ->set('floating', true);
                        }
                    );

                $form->add('variant/dimension/weight', NumberField::class)
                    ->label($this->trans('shopgo.product.field.weight'))
                    ->step('0.0001')
                    ->set('floating', true);

                $form->add('shippings', ShippingModalField::class)
                    ->label($this->trans('shopgo.product.field.shippings'))
                    ->multiple(true);
            }
        );

        $form->fieldset(
            'info3',
            function (Form $form) {
                $form->add('model', TextField::class)
                    ->label($this->trans('shopgo.product.field.model'));

                $form->add('variant/sku', TextField::class)
                    ->label($this->trans('shopgo.product.field.sku'));

                $form->add('variant/upc', TextField::class)
                    ->label($this->trans('shopgo.product.field.upc'));

                $form->add('variant/ean', TextField::class)
                    ->label($this->trans('shopgo.product.field.ean'));

                $form->add('variant/jan', TextField::class)
                    ->label($this->trans('shopgo.product.field.jan'));

                $form->add('variant/isbn', TextField::class)
                    ->label($this->trans('shopgo.product.field.isbn'));

                $form->add('variant/mpn', TextField::class)
                    ->label($this->trans('shopgo.product.field.mpn'));
            }
        );

        $form->fieldset(
            'images',
            function (Form $form) {
                $form->add('variant/cover', SingleImageDragField::class)
                    ->label($this->trans('shopgo.product.field.cover'))
                    ->crop(true)
                    ->width(800)
                    ->height(800)
                    ->ajax(true)
                    ->showSizeNotice(true);

                $form->add('variant/images', MultiUploaderField::class)
                    ->label($this->trans('shopgo.product.field.images'))
                    ->configureForm(
                        function (Form $form) {
                            $form->add('title', TextField::class)
                                ->label($this->trans('unicorn.field.title'));
                        }
                    );
            }
        );

        $form->fieldset(
            'about',
            function (Form $form) {
                $form->add('intro', TinymceEditorField::class)
                    ->label($this->trans('shopgo.product.field.intro'))
                    ->editorOptions(
                        [
                            'height' => 450
                        ]
                    );

                $form->add('description', TinymceEditorField::class)
                    ->label($this->trans('unicorn.field.description'))
                    ->editorOptions(
                        [
                            'height' => 550
                        ]
                    );
            }
        );

        $form->fieldset(
            'seo',
            function (Form $form) {
                $form->add('meta/title', TextField::class)
                    ->label($this->trans('shopgo.product.field.meta.title'))
                    ->maxlength(255);

                $form->add('meta/description', TextareaField::class)
                    ->label($this->trans('shopgo.product.field.meta.description'))
                    ->rows(4);

                $form->add('meta/keywords', TextField::class)
                    ->label($this->trans('shopgo.product.field.meta.keywords'))
                    ->maxlength(255);
            }
        );

        $form->add('id', HiddenField::class);

        $form->fieldset(
            'meta',
            function (Form $form) {
                $form->add('category_id', CategoryModalField::class)
                    ->label($this->trans('shopgo.product.field.main.category'))
                    ->categoryType('product');

                $form->add('sub_categories', CategoryListField::class)
                    ->label($this->trans('shopgo.product.field.sub.category'))
                    ->categoryType('product')
                    ->addClass('has-tom-select')
                    ->multiple(true);

                $form->add('state', SwitcherField::class)
                    ->label($this->trans('unicorn.field.published'))
                    ->circle(true)
                    ->color('success')
                    ->defaultValue('1');

                $form->add('hide', SwitcherField::class)
                    ->label($this->trans('shopgo.product.field.hide'))
                    ->circle(true)
                    ->color('warning')
                    ->defaultValue('1');

                $form->add('publish_up', CalendarField::class)
                    ->label($this->trans('shopgo.product.field.publish_up'));

                $form->add('publish_down', CalendarField::class)
                    ->label($this->trans('shopgo.product.field.publish_down'));

                $form->add('created', CalendarField::class)
                    ->label($this->trans('unicorn.field.created'));

                $form->add('modified', CalendarField::class)
                    ->label($this->trans('unicorn.field.modified'));

                $form->add('created_by', UserModalField::class)
                    ->label($this->trans('unicorn.field.author'));

                $form->add('modified_by', UserModalField::class)
                    ->label($this->trans('unicorn.field.modified_by'));

                $form->add('primary_variant_id', HiddenField::class);
            }
        );
    }
}

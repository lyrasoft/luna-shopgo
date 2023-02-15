<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Module\Admin\AdditionalPurchase\Form;

use Lyrasoft\ShopGo\Entity\Product;
use Lyrasoft\ShopGo\Field\ProductModalField;
use Lyrasoft\ShopGo\Field\ProductVariantListField;
use Lyrasoft\ShopGo\Traits\CurrencyAwareTrait;
use Lyrasoft\Luna\Field\UserModalField;
use Unicorn\Field\CalendarField;
use Unicorn\Field\SwitcherField;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Form\Field\HiddenField;
use Windwalker\Form\Field\ListField;
use Windwalker\Form\Field\NumberField;
use Windwalker\Form\Field\TextField;
use Windwalker\Form\FieldDefinitionInterface;
use Windwalker\Form\Form;
use Windwalker\Query\Query;

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

        $form->fieldset(
            'targets',
            function (Form $form) {
                $form->add('products', ProductModalField::class)
                    ->label($this->trans('shopgo.additional.purchase.field.target.products'))
                    ->callback('targetSelected')
                    ->hasImage(true)
                    ->multiple(true);
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

                $form->add('publish_up', CalendarField::class)
                    ->label($this->trans('shopgo.additional.purchase.field.publish_up'));

                $form->add('publish_down', CalendarField::class)
                    ->label($this->trans('shopgo.additional.purchase.field.publish_down'));

                $form->add('created', CalendarField::class)
                    ->label($this->trans('unicorn.field.created'));

                $form->add('modified', CalendarField::class)
                    ->label($this->trans('unicorn.field.modified'));

                $form->add('created_by', UserModalField::class)
                    ->label($this->trans('unicorn.field.author'));

                $form->add('modified_by', UserModalField::class)
                    ->label($this->trans('unicorn.field.modified_by'));
            }
        );

        $form->add('id', HiddenField::class);
    }
}

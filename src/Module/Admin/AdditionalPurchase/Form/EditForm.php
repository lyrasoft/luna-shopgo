<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Module\Admin\AdditionalPurchase\Form;

use App\Field\ProductModalField;
use App\Field\ProductVariantListField;
use App\Traits\CurrencyAwareTrait;
use Lyrasoft\Luna\Field\UserModalField;
use Unicorn\Field\CalendarField;
use Unicorn\Field\LayoutField;
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
    use CurrencyAwareTrait;

    public function __construct(protected int $productId)
    {
    }

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
            'basic',
            function (Form $form) {
                $form->add('attach_product_id', ProductModalField::class)
                    ->label($this->trans('shopgo.additional.purchase.attach.product'))
                    ->required(true);

                $form->add('attach_variant_id', ProductVariantListField::class)
                    ->label($this->trans('shopgo.additional.purchase.field.attach_variant_id'))
                    ->option($this->trans('unicorn.select.placeholder'), '')
                    ->setProductId($this->productId)
                    ->addClass('has-tom-select');

                $form->add('products', ProductModalField::class)
                    ->label($this->trans('shopgo.additional.purchase.field.products'))
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

                $form->add('price', NumberField::class)
                    ->label($this->trans('shopgo.additional.purchase.field.price'))
                    ->addClass('form-control-lg')
                    ->step($this->getMainCurrency()->getInputStep());

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

<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyraoft\ShopGo\Module\Admin\AdditionalPurchase\Form;

use Lyraoft\ShopGo\Entity\Product;
use Lyraoft\ShopGo\Field\ProductModalField;
use Lyraoft\ShopGo\Field\ProductVariantListField;
use Lyraoft\ShopGo\Traits\CurrencyAwareTrait;
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
    use CurrencyAwareTrait;

    public function __construct(protected ?Product $product)
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
                    ->label($this->trans('shopgo.additional.purchase.field.attach.product'))
                    ->required(true);

                $variants = $this->product?->getVariants() ?? 0;

                $form->add('attach_variant_id', ProductVariantListField::class)
                    ->label($this->trans('shopgo.additional.purchase.field.attach_variant'))
                    // ->option($this->trans('unicorn.select.placeholder'), '')
                    ->setProductId($this->product?->getId() ?: 0)
                    ->configureQuery(
                        function (Query $query) use ($variants) {
                            if ($variants > 0) {
                                $query->where('primary', 0);
                            } else {
                                $query->where('primary', 1);
                            }
                        }
                    )
                    ->tapIf(
                        $variants === 0,
                        fn (ListField $field) => $field->addWrapperClass('d-none')
                    )
                    ->addClass('has-tom-select')
                    ->required(true);

                $form->add('products', ProductModalField::class)
                    ->label($this->trans('shopgo.additional.purchase.field.target.products'))
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

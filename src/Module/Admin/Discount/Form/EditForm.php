<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Module\Admin\Discount\Form;

use App\Enum\DiscountApplyTo;
use App\Enum\DiscountCombine;
use App\Enum\DiscountMethod;
use App\Field\DiscountModalField;
use App\Field\PaymentModalField;
use App\Field\ProductModalField;
use App\Field\ShippingModalField;
use Lyrasoft\Luna\Field\CategoryModalField;
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
                    ->label($this->trans('shopgo.discount.field.quantity'))
                    ->min(0)
                    ->help($this->trans('shopgo.discount.help.zero.or.empty.is.no.limit'));

                $form->add('min_price', NumberField::class)
                    ->label($this->trans('shopgo.discount.field.min_price'))
                    ->step('0.0001')
                    ->min(0)
                    ->description($this->trans('shopgo.discount.field.min_price.desc'))
                    ->help($this->trans('shopgo.discount.help.zero.or.empty.is.no.limit'));

                $form->add('min_cart_items', NumberField::class)
                    ->label($this->trans('shopgo.discount.field.min_cart_items'))
                    ->min(0)
                    ->help($this->trans('shopgo.discount.help.zero.or.empty.is.no.limit'));

                $form->add('min_cart_price', NumberField::class)
                    ->label($this->trans('shopgo.discount.field.min_cart_price'))
                    ->step('0.0001')
                    ->min(0)
                    ->help($this->trans('shopgo.discount.help.zero.or.empty.is.no.limit'));

                $form->add('times_per_user', NumberField::class)
                    ->label($this->trans('shopgo.discount.field.times_per_user'))
                    ->min(0)
                    ->help($this->trans('shopgo.discount.help.zero.or.empty.is.no.limit'));

                $form->add('first_buy', NumberField::class)
                    ->label($this->trans('shopgo.discount.field.first_buy'))
                    ->min(0)
                    ->description($this->trans('shopgo.discount.field.first_buy.desc'))
                    ->help($this->trans('shopgo.discount.help.zero.or.empty.is.no.limit'));

                $form->add('after_registered', NumberField::class)
                    ->label($this->trans('shopgo.discount.field.after_registered'))
                    ->min(0)
                    ->help($this->trans('shopgo.discount.help.zero.or.empty.is.no.limit'));

                $form->add('can_rollback', SwitcherField::class)
                    ->label($this->trans('shopgo.discount.field.can_rollback'))
                    ->circle(true)
                    ->color('primary')
                    ->defaultValue('1');
            }
        );

        $form->fieldset(
            'specifies',
            function (Form $form) {
                $form->add('users', UserModalField::class)
                    ->label($this->trans('shopgo.discount.field.users'))
                    ->multiple(true)
                    ->help($this->trans('shopgo.discount.field.users.help'));

                $form->add('categories', CategoryModalField::class)
                    ->label($this->trans('shopgo.discount.field.categories'))
                    ->categoryType('product')
                    ->multiple(true)
                    ->help($this->trans('shopgo.discount.field.categories.help'));

                $form->add('prodcuts', ProductModalField::class)
                    ->label($this->trans('shopgo.discount.field.prodcuts'))
                    ->hasImage(true)
                    ->multiple(true)
                    ->help($this->trans('shopgo.discount.field.products.help'));

                $form->add('payments', PaymentModalField::class)
                    ->label($this->trans('shopgo.discount.field.payments'))
                    ->multiple(true)
                    ->help($this->trans('shopgo.discount.field.payments.help'));

                $form->add('shippings', ShippingModalField::class)
                    ->label($this->trans('shopgo.discount.field.shippings'))
                    ->multiple(true)
                    ->help($this->trans('shopgo.discount.field.shippings.help'));
            }
        );

        $form->fieldset(
            'combine',
            function (Form $form) {

                $form->add('combine', ListField::class)
                    ->label($this->trans('shopgo.discount.field.combine'))
                    ->registerFromEnums(DiscountCombine::class, $this->lang)
                    ->help(
                        <<<HTML
                        <div>{$this->trans('shopgo.discount.field.combine.help')}</div>
                        <ul>
                            <li>{$this->trans('shopgo.discount.field.combine.help.option.open')}</li>
                            <li>{$this->trans('shopgo.discount.field.combine.help.option.stop')}</li>
                            <li>{$this->trans('shopgo.discount.field.combine.help.option.includes')}</li>
                            <li>{$this->trans('shopgo.discount.field.combine.help.option.excludes')}</li>
                        </ul>
                        HTML
                    );

                $form->add('combine_targets', DiscountModalField::class)
                    ->label($this->trans('shopgo.discount.field.combine_targets'))
                    ->multiple(true);
            }
        );

        $form->fieldset(
            'pricing',
            function (Form $form) {
                $form->add('free_shipping', NumberField::class)
                    ->label($this->trans('shopgo.discount.field.free_shipping'));

                $form->add('method', ListField::class)
                    ->label($this->trans('shopgo.discount.field.method'))
                    ->registerFromEnums(DiscountMethod::class, $this->lang);

                $form->add('price', NumberField::class)
                    ->label($this->trans('shopgo.discount.field.price'))
                    ->step('0.0001')
                    ->help($this->trans('shopgo.discount.field.price.help'));

                $form->add('apply_to', ListField::class)
                    ->label($this->trans('shopgo.discount.field.apply_to'))
                    ->registerFromEnums(DiscountApplyTo::class, $this->lang)
                    ->help(
                        <<<HTML
                        <div>{$this->trans('shopgo.discount.field.apply_to.help')}</div>
                        <ul>
                            <li>{$this->trans('shopgo.discount.field.apply_to.help.option.order')}</li>
                            <li>{$this->trans('shopgo.discount.field.apply_to.help.option.products')}</li>
                            <li>{$this->trans('shopgo.discount.field.apply_to.help.option.matched')}</li>
                        </ul>
                        HTML
                    );

                $form->add('apply_products', ProductModalField::class)
                    ->label($this->trans('shopgo.discount.field.apply_products'))
                    ->hasImage(true)
                    ->multiple(true)
                    ->set('showon', ['apply_to' => DiscountApplyTo::PRODUCTS()]);
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

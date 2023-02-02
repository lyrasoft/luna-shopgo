<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Module\Admin\Currency\Form;

use Lyrasoft\ShopGo\Enum\SignPosition;
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
            ->addFilter('trim')
            ->required(true);

        $form->fieldset(
            'basic',
            function (Form $form) {
                $form->add('code', TextField::class)
                    ->label($this->trans('shopgo.currency.field.code'))
                    ->required(true);

                $form->add('sign', TextField::class)
                    ->label($this->trans('shopgo.currency.field.sign'));

                $form->add('sign_position', ListField::class)
                    ->label($this->trans('shopgo.currency.field.sign.position'))
                    ->defaultValue(SignPosition::START)
                    ->registerOptions(SignPosition::getTransItems($this->lang));

                $form->add('exchange_rate', TextField::class)
                    ->label($this->trans('shopgo.currency.field.exchange.rate'));

                $form->add('decimal_place', NumberField::class)
                    ->label($this->trans('shopgo.currency.field.decimal.place'))
                    ->range(0, 5);

                $form->add('decimal_point', TextField::class)
                    ->label($this->trans('shopgo.currency.field.decimal.point'));

                $form->add('num_separator', TextField::class)
                    ->label($this->trans('shopgo.currency.field.num.separator'));

                $form->add('space', SwitcherField::class)
                    ->label($this->trans('shopgo.currency.field.space'))
                    ->defaultValue(0)
                    ->circle(true)
                    ->size('sm')
                    ->color('primary');
            }
        );

        $form->fieldset(
            'meta',
            function (Form $form) {
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
    }
}

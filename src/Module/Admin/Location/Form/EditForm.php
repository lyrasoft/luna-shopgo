<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Module\Admin\Location\Form;

use App\Entity\Location;
use App\Enum\LocationType;
use Lyrasoft\Luna\Field\CategoryModalField;
use Lyrasoft\Luna\Field\UserModalField;
use Unicorn\Field\CalendarField;
use Unicorn\Field\CascadeSelectField;
use Unicorn\Field\SwitcherField;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Core\Router\Navigator;
use Windwalker\Form\Field\HiddenField;
use Windwalker\Form\Field\ListField;
use Windwalker\Form\Field\TextareaField;
use Windwalker\Form\Field\TextField;
use Windwalker\Form\FieldDefinitionInterface;
use Windwalker\Form\Form;
use Windwalker\ORM\NestedSetMapper;
use Windwalker\ORM\ORM;

use function Windwalker\raw;

/**
 * The EditForm class.
 */
class EditForm implements FieldDefinitionInterface
{
    use TranslatorTrait;

    public function __construct(
        protected Navigator $nav,
        protected ORM $orm,
        protected ?int $id = null,
        protected ?LocationType $type = null,
    ) {
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
                $form->add('parent_id', CascadeSelectField::class)
                    ->label($this->trans('shopgo.location.field.parent'))
                    ->ignoreSelfWith($this->id)
                    ->labels(
                        [
                            LocationType::CONTINENT()->getTitle($this->lang),
                            LocationType::COUNTRY()->getTitle($this->lang),
                            LocationType::STATE()->getTitle($this->lang),
                            LocationType::CITY()->getTitle($this->lang),
                        ]
                    )
                    ->cascadeSelectOptions(
                        [
                            'onSelectInit' => raw(
                                "
                            (event) => setTimeout(() => u.\$ui.tomSelect(event.detail.el), 0)
                            "
                            ),
                        ]
                    )
                    ->ajaxUrl(
                        $this->nav->to('location_ajax')->task('parentList')
                    )
                    ->pathHandler(
                        function (mixed $value) {
                            if (!$value) {
                                return [];
                            }

                            /** @var NestedSetMapper<Location> $mapper */
                            $mapper = $this->orm->mapper(Location::class);

                            return $mapper->getPath($value)
                                ->removeFirst()
                                ->column('id')
                                ->dump();
                        }
                    );

                $form->add('native', TextField::class)
                    ->label($this->trans('shopgo.location.field.native'));

                if ($this->type && $this->type->equals(LocationType::COUNTRY())) {
                    $form->add('region', TextField::class)
                        ->label($this->trans('shopgo.location.field.region'));

                    $form->add('subregion', TextField::class)
                        ->label($this->trans('shopgo.location.field.subregion'));

                    $form->add('code', TextField::class)
                        ->label($this->trans('shopgo.location.field.code'))
                        ->maxlength(32);

                    $form->add('code3', TextField::class)
                        ->label($this->trans('shopgo.location.field.code3'))
                        ->maxlength(3);

                    $form->add('call_prefix', TextField::class)
                        ->label($this->trans('shopgo.location.field.call_prefix'));

                    $form->add('address_format', TextareaField::class)
                        ->label($this->trans('shopgo.location.field.address_format'))
                        ->rows(5);

                    $form->add('postcode_required', SwitcherField::class)
                        ->label($this->trans('shopgo.location.field.postcode_required'))
                        ->color('primary')
                        ->circle(true);

                    $form->add('has_states', SwitcherField::class)
                        ->label($this->trans('shopgo.location.field.has_states'))
                        ->color('primary')
                        ->circle(true);
                } else {
                    $form->add('code', TextField::class)
                        ->label($this->trans('shopgo.location.field.code'))
                        ->maxlength(32);
                }
            }
        );

        $form->fieldset(
            'meta',
            function (Form $form) {
                $form->add('type', ListField::class)
                    ->label($this->trans('unicorn.field.type'))
                    ->addClass('pe-none bg-light user-select-none')
                    ->registerFromEnums(LocationType::class, $this->lang);

                $form->add('category_id', CategoryModalField::class)
                    ->label($this->trans('shopgo.location.field.category'))
                    ->categoryType('location');

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

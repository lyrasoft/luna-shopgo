<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Service;

use Lyrasoft\Luna\Entity\Category;
use Lyrasoft\ShopGo\Data\ListOption;
use Lyrasoft\ShopGo\Entity\Product;
use Lyrasoft\ShopGo\Entity\ProductAttribute;
use Lyrasoft\ShopGo\Entity\ProductAttributeMap;
use Lyrasoft\ShopGo\Entity\ShopCategoryMap;
use Lyrasoft\ShopGo\Enum\ProductAttributeType;
use Unicorn\Field\SwitcherField;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Data\Collection;
use Windwalker\Form\Field\ListField;
use Windwalker\Form\Field\TextField;
use Windwalker\Form\Form;
use Windwalker\ORM\ORM;
use Windwalker\Query\Query;

/**
 * The ProductAttributeService class.
 */
class ProductAttributeService
{
    use TranslatorTrait;

    public function __construct(protected ORM $orm)
    {
    }

    /**
     * @param  Product  $product
     *
     * @return  array{ 0: Collection, 1: Collection }
     */
    public function getAttributesAndGroupsWithValues(Product $product): array
    {
        $groups = $this->getGroupsByProductCategoryId($product->getCategoryId());
        $groupIds = $groups->column('id')
            ->prepend(0)
            ->unique()
            ->dump();

        $attributes = $this->getAttributesOfGroups($groupIds);

        /** @var ProductAttributeMap[] $maps */
        $maps = $this->getProductAttributeValues($product->getId())->keyBy('attributeId');

        foreach ($attributes as $attribute) {
            $attribute->setValue($maps[$attribute->getId()]?->getValue() ?? '');
        }

        return [$attributes, $groups];
    }

    /**
     * @param  int  $categoryId
     *
     * @return  array{ 0: Collection<ProductAttribute>, 1: Collection<Category> }
     */
    public function getAttributesAndGroupsByCategoryId(int $categoryId): array
    {
        $groups = $this->getGroupsByProductCategoryId($categoryId);
        $groupIds = $groups->column('id')
            ->prepend(0)
            ->unique()
            ->dump();

        $attributes = $this->getAttributesOfGroups($groupIds);

        return [$attributes, $groups];
    }

    public function prepareEditForm(Product $product, Form $form, &$fieldsets = []): Form
    {
        [$attributes, $groups] = $this->getAttributesAndGroupsByCategoryId($product->getCategoryId());

        $attributeSets = $attributes->groupBy('categoryId');

        /**
         * @param  Category|null  $group
         *
         * @return  void
         */
        $register = function (?Category $group) use (&$fieldsets, $attributeSets, $form) {
            $groupId = $group?->getId() ?? 0;
            $fieldsets[] = $fieldset = 'attr-' . $groupId;
            /** @var ProductAttribute[] $attributes */
            $attributes = $attributeSets[$groupId];

            if (!$attributes) {
                return;
            }

            $form->fieldset($fieldset)
                ->title($group?->getTitle() ?? '')
                ->register(
                    function (Form $form) use ($attributes) {
                        foreach ($attributes as $attribute) {
                            $field = match ($attribute->getType()) {
                                ProductAttributeType::BOOL() => $this->prepareFieldBool($form, $attribute),
                                ProductAttributeType::TEXT() => $this->prepareFieldText($form, $attribute),
                                ProductAttributeType::SELECT() => $this->prepareFieldSelect($form, $attribute),
                            };

                            if (!$attribute->shouldDisplay()) {
                                $field->set('no_display', true);
                            }
                        }
                    }
                );
        };

        $form->ns(
            'attrs',
            function (Form $form) use ($groups, $register) {
                $register(null);

                foreach ($groups as $group) {
                    $register($group);
                }
            }
        );

        return $form;
    }

    /**
     * @param  int  $productCategoryId
     *
     * @return  Collection
     */
    protected function getGroupsByProductCategoryId(int $productCategoryId): Collection
    {
        return $this->orm->from(Category::class, 'group')
            ->where('group.type', 'attribute')
            ->whereExists(
                function (Query $query) use ($productCategoryId) {
                    return $query->from(ShopCategoryMap::class)
                        ->whereRaw('type = %q', 'attribute_group')
                        ->whereRaw('category_id = %a', $productCategoryId)
                        ->whereRaw('target_id = group.id');
                }
            )
            ->order('group.lft', 'ASC')
            ->all(Category::class);
    }

    /**
     * @param  array<int>  $groupIds
     *
     * @return  Collection<ProductAttribute>
     */
    protected function getAttributesOfGroups(array $groupIds): Collection
    {
        return $this->orm->from(ProductAttribute::class)
            ->where('category_id', $groupIds)
            ->where('state', 1)
            ->order('ordering', 'ASC')
            ->all(ProductAttribute::class);
    }

    /**
     * @param  int  $productId
     *
     * @return  Collection<ProductAttributeMap>
     */
    public function getProductAttributeValues(int $productId): Collection
    {
        return $this->orm->findList(
            ProductAttributeMap::class,
            ['product_id' => $productId]
        )
            ->all();
    }

    public function renderValue(ProductAttribute $attribute): string
    {
        $value = $attribute->getValue();

        return match ($attribute->getType()) {
            ProductAttributeType::BOOL() => $value
                ? $this->trans('unicorn.core.yes')
                : $this->trans('unicorn.core.no'),
            ProductAttributeType::TEXT() => $value,
            ProductAttributeType::SELECT() => $value,
        };
    }

    protected function prepareFieldBool(Form $form, ProductAttribute $attribute): SwitcherField
    {
        return $form->add($attribute->getKey(), SwitcherField::class)
            ->label($attribute->getTitle())
            ->circle(true)
            ->color('primary');
    }

    protected function prepareFieldText(Form $form, ProductAttribute $attribute): TextField
    {
        return $form->add($attribute->getKey(), TextField::class)
            ->label($attribute->getTitle());
    }

    protected function prepareFieldSelect(Form $form, ProductAttribute $attribute): ListField
    {
        return $form->add($attribute->getKey(), ListField::class)
            ->label($attribute->getTitle())
            ->register(
                function (ListField $field) use ($attribute) {
                    foreach ($attribute->getOptions() as $option) {
                        $field->option($option->getText(), $option->getValue());
                    }
                }
            );
    }
}

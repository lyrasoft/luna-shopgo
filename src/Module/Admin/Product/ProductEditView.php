<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyraoft\ShopGo\Module\Admin\Product;

use Lyraoft\ShopGo\Entity\Discount;
use Lyraoft\ShopGo\Entity\Product;
use Lyraoft\ShopGo\Entity\ProductVariant;
use Lyraoft\ShopGo\Entity\ShopCategoryMap;
use Lyraoft\ShopGo\Enum\DiscountType;
use Lyraoft\ShopGo\Module\Admin\Product\Form\EditForm;
use Lyraoft\ShopGo\Repository\ProductRepository;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Attributes\ViewModel;
use Windwalker\Core\Form\FormFactory;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\View\View;
use Windwalker\Core\View\ViewModelInterface;
use Windwalker\DI\Attributes\Autowire;
use Windwalker\ORM\ORM;

use function Windwalker\collect;

/**
 * The ProductEditView class.
 */
#[ViewModel(
    layout: 'product-edit',
    js: [
        'product-edit.js',
        'product-variants-edit.js',
        'product-discounts-edit.js',
    ]
)]
class ProductEditView implements ViewModelInterface
{
    use TranslatorTrait;

    public function __construct(
        protected ORM $orm,
        protected FormFactory $formFactory,
        protected Navigator $nav,
        #[Autowire] protected ProductRepository $repository
    ) {
    }

    /**
     * Prepare
     *
     * @param  AppContext  $app
     * @param  View        $view
     *
     * @return  mixed
     */
    public function prepare(AppContext $app, View $view): mixed
    {
        $id = $app->input('id');

        /** @var Product $item */
        $item = $this->repository->getItem($id);

        $form = $this->formFactory
            ->create(EditForm::class)
            ->setNamespace('item')
            ->fill(
                $this->repository->getState()->getAndForget('edit.data')
                    ?: $this->orm->extractEntity($item)
            );

        $variants = collect();
        $discounts = collect();

        if ($item) {
            // Main Variant
            $mainVariant = $this->orm->findOne(
                ProductVariant::class,
                [
                    'product_id' => $item->getId(),
                    'primary' => 1
                ]
            );

            $form->fill(
                [
                    'variant' => $this->repository->getState()->getAndForget('edit.data')
                        ?: $this->orm->extractEntity($mainVariant)
                ]
            );

            // Variants
            $variants = $this->orm->from(ProductVariant::class)
                ->where('product_id', $item->getId())
                ->where('primary', '!=', 1)
                ->all(ProductVariant::class);

            // Discounts
            $discounts = $this->orm->from(Discount::class)
                ->where('product_id', $item->getId())
                ->where('type', DiscountType::PRODUCT())
                ->order('ordering', 'ASC')
                ->all(Discount::class);

            // Sub Categories
            $subCategoryIds = $this->orm->select('category_id')
                ->from(ShopCategoryMap::class, 'map')
                ->where('map.target_id', $item->getId())
                ->where('map.type', 'product')
                ->where('primary', 0)
                ->loadColumn()
                ->dump();

            $form->fill(
                ['sub_categories' => $subCategoryIds]
            );
        }

        $this->prepareMetadata($app, $view);

        return compact('form', 'id', 'item', 'variants', 'discounts');
    }

    /**
     * Prepare Metadata and HTML Frame.
     *
     * @param  AppContext  $app
     * @param  View        $view
     *
     * @return  void
     */
    protected function prepareMetadata(AppContext $app, View $view): void
    {
        $view->getHtmlFrame()
            ->setTitle(
                $this->trans('unicorn.title.edit', title: 'Product')
            );
    }
}

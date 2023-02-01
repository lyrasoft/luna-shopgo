<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyraoft\ShopGo\Module\Admin\ProductAttributeGroup;

use App\Entity\ProductAttributeGroup;
use Lyraoft\ShopGo\Entity\ShopCategoryMap;
use Lyraoft\ShopGo\Module\Admin\ProductAttributeGroup\Form\EditForm;
use Lyraoft\ShopGo\Repository\ProductAttributeGroupRepository;
use Lyrasoft\Luna\Entity\Category;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Attributes\ViewModel;
use Windwalker\Core\Form\FormFactory;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\View\View;
use Windwalker\Core\View\ViewModelInterface;
use Windwalker\DI\Attributes\Autowire;
use Windwalker\ORM\ORM;

/**
 * The ProductAttributeGroupEditView class.
 */
#[ViewModel(
    layout: 'product-attribute-group-edit',
    js: 'product-attribute-group-edit.js'
)]
class ProductAttributeGroupEditView implements ViewModelInterface
{
    use TranslatorTrait;

    public function __construct(
        protected ORM $orm,
        protected FormFactory $formFactory,
        protected Navigator $nav,
        #[Autowire] protected ProductAttributeGroupRepository $repository
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

        /** @var Category $item */
        $item = $this->repository->getItem($id);

        $form = $this->formFactory
            ->create(EditForm::class)
            ->setNamespace('item')
            ->fill(
                $this->repository->getState()->getAndForget('edit.data')
                    ?: $this->orm->extractEntity($item)
            );

        if ($item) {
            $categoryIds = $this->orm->select('category_id')
                ->from(ShopCategoryMap::class)
                ->where('type', 'attribute_group')
                ->where('target_id', $item->getId())
                ->loadColumn()
                ->dump();

            $form->fill(['categories' => $categoryIds]);
        }

        $this->prepareMetadata($app, $view);

        return compact('form', 'id', 'item');
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
                $this->trans('unicorn.title.edit', title: $this->trans('shopgo.product.attribute.group.title'))
            );
    }
}

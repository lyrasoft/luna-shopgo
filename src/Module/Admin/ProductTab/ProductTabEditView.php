<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Module\Admin\ProductTab;

use Lyrasoft\ShopGo\Entity\ProductTab;
use Lyrasoft\ShopGo\Entity\ShopCategoryMap;
use Lyrasoft\ShopGo\Module\Admin\ProductTab\Form\EditForm;
use Lyrasoft\ShopGo\Repository\ProductTabRepository;
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
 * The ProductTabEditView class.
 */
#[ViewModel(
    layout: 'product-tab-edit',
    js: 'product-tab-edit.js'
)]
class ProductTabEditView implements ViewModelInterface
{
    use TranslatorTrait;

    public function __construct(
        protected ORM $orm,
        protected FormFactory $formFactory,
        protected Navigator $nav,
        #[Autowire] protected ProductTabRepository $repository
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

        /** @var ProductTab $item */
        $item = $this->repository->getItem($id);

        $categoryIds = $this->orm->findColumn(
            ShopCategoryMap::class,
            'category_id',
            [
                'type' => 'tab',
                'target_id' => $item?->getId()
            ]
        )->dump();

        $form = $this->formFactory
            ->create(EditForm::class)
            ->setNamespace('item')
            ->fill(
                $this->repository->getState()->getAndForget('edit.data')
                    ?: $this->orm->extractEntity($item)
            )
            ->fill(
                [
                    'categories' => $categoryIds
                ]
            );

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
                $this->trans('unicorn.title.edit', title: $this->trans('shopgo.product.tab.title'))
            );
    }
}

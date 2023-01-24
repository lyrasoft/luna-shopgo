<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Module\Admin\Product;

use App\Entity\Product;
use App\Entity\ProductVariant;
use App\Module\Admin\Product\Form\EditForm;
use App\Repository\ProductRepository;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Attributes\ViewModel;
use Windwalker\Core\Form\FormFactory;
use Windwalker\Core\Html\HtmlFrame;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\View\View;
use Windwalker\Core\View\ViewModelInterface;
use Windwalker\DI\Attributes\Autowire;
use Windwalker\ORM\ORM;

/**
 * The ProductEditView class.
 */
#[ViewModel(
    layout: 'product-edit',
    js: 'product-edit.js'
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

        $rememberedData = $this->repository->getState()->getAndForget('edit.data');

        $form = $this->formFactory
            ->create(EditForm::class)
            ->setNamespace('item')
            ->fill(
                $this->repository->getState()->getAndForget('edit.data')
                    ?: $this->orm->extractEntity($item)
            );

        if ($item) {
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
                $this->trans('unicorn.title.edit', title: 'Product')
            );
    }
}

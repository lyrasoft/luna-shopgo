<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Module\Admin\AdditionalPurchase;

use App\Entity\AdditionalPurchase;
use App\Entity\AdditionalPurchaseMap;
use App\Entity\Product;
use App\Entity\ProductVariant;
use App\Module\Admin\AdditionalPurchase\Form\EditForm;
use App\Repository\AdditionalPurchaseRepository;
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

use function Windwalker\collect;

/**
 * The AdditionalPurchaseEditView class.
 */
#[ViewModel(
    layout: 'additional-purchase-edit',
    js: 'additional-purchase-edit.js'
)]
class AdditionalPurchaseEditView implements ViewModelInterface
{
    use TranslatorTrait;

    public function __construct(
        protected ORM $orm,
        protected FormFactory $formFactory,
        protected Navigator $nav,
        #[Autowire] protected AdditionalPurchaseRepository $repository
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

        /** @var AdditionalPurchase $item */
        $item = $this->repository->getItem($id);

        $currentItem = $this->repository->getState()->getAndForget('edit.data')
            ?: $this->orm->extractEntity($item);

        $productId = $currentItem['attach_product_id'] ?? 0;

        $product = $this->orm->findOne(Product::class, $productId);

        $form = $this->formFactory
            ->create(EditForm::class, product: $product)
            ->setNamespace('item');

        if ($item) {
            $productIds = $this->orm->findColumn(
                AdditionalPurchaseMap::class,
                'target_product_id',
                ['additional_purchase_id' => $item->getId()]
            )->dump();

            $form->fill(['products' => $productIds]);
        }

        $form->fill($currentItem);

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
                $this->trans('unicorn.title.edit', title: $this->trans('shopgo.additional.purchase.title'))
            );
    }
}

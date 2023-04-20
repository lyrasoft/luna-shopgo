<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Module\Admin\AdditionalPurchase;

use Lyrasoft\ShopGo\Entity\AdditionalPurchase;
use Lyrasoft\ShopGo\Entity\AdditionalPurchaseAttachment;
use Lyrasoft\ShopGo\Entity\AdditionalPurchaseTarget;
use Lyrasoft\ShopGo\Entity\Product;
use Lyrasoft\ShopGo\Entity\ProductVariant;
use Lyrasoft\ShopGo\Module\Admin\AdditionalPurchase\Form\EditForm;
use Lyrasoft\ShopGo\Repository\AdditionalPurchaseRepository;
use Lyrasoft\ShopGo\Traits\CurrencyAwareTrait;
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
use function Windwalker\Query\val;

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
    use CurrencyAwareTrait;

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

        $form = $this->formFactory
            ->create(EditForm::class)
            ->setNamespace('item')
            ->fill(
                $this->repository->getState()->getAndForget('edit.data')
                    ?: $this->orm->extractEntity($item)
            );

        if ($item) {
            $productIds = $this->orm->findColumn(
                AdditionalPurchaseTarget::class,
                'product_id',
                ['additional_purchase_id' => $item->getId()]
            )->dump();

            $form->fill(['products' => $productIds]);
        }

        // Attachments
        $attachments = $this->orm->from(AdditionalPurchaseAttachment::class)
            ->where('additional_purchase_id', $item?->getId())
            ->all(AdditionalPurchaseAttachment::class)
            ->keyBy('variantId');

        $productIds = $attachments->column('productId')->unique()->dump();

        $variants = $this->orm->from(ProductVariant::class, 'variant')
            ->where('product_id', $productIds ?: [0])
            ->all(ProductVariant::class);

        /** @var ProductVariant $variant */
        foreach ($variants as $variant) {
            $variant->attachment = $attachments[$variant->getId()] ?? null;
        }

        $variantSet = $variants->groupBy('productId');

        /** @var Product[] $products */
        $products = $this->orm->from(Product::class)
            ->leftJoin(
                ProductVariant::class,
                'variant',
                [
                    ['variant.product_id', 'product.id'],
                    ['variant.primary', val(1)],
                ]
            )
            ->where('product.id', $productIds ?: [0])
            ->groupByJoins()
            ->all(Product::class);

        $attachmentsData = [];

        foreach ($products as $product) {
            $variants = $variantSet[$product->getId()] ?? collect();

            $variants = $variants->filter(
                function (ProductVariant $variant) use ($product) {
                    if ($product->getVariants() === 0) {
                        return $variant->isPrimary();
                    }

                    return !$variant->isPrimary();
                }
            )->values();

            $attachmentsData[] = compact('product', 'variants');
        }

        $this->prepareMetadata($app, $view);

        return compact('form', 'id', 'item', 'attachmentsData');
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

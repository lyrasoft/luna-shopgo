<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Module\Admin\Shipping;

use Lyrasoft\ShopGo\Entity\Shipping;
use Lyrasoft\ShopGo\Module\Admin\Shipping\Form\EditForm;
use Lyrasoft\ShopGo\Repository\ShippingRepository;
use Lyrasoft\ShopGo\Shipping\ShippingService;
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
 * The ShippingEditView class.
 */
#[ViewModel(
    layout: 'shipping-edit',
    js: 'shipping-edit.js'
)]
class ShippingEditView implements ViewModelInterface
{
    use TranslatorTrait;

    public function __construct(
        protected ORM $orm,
        protected FormFactory $formFactory,
        protected Navigator $nav,
        protected ShippingService $shippingService,
        #[Autowire] protected ShippingRepository $repository
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

        /** @var Shipping $item */
        $item = $this->repository->getItem($id);
        $type = $item?->getType() ?? $app->input('type');

        $typeClass = $this->shippingService->getTypeClass($type);
        $typeInstance = $this->shippingService->createTypeInstance($type);

        $form = $this->formFactory
            ->create(EditForm::class)
            ->setNamespace('item')
            ->defineFormFields($typeInstance)
            ->fill(
                $this->repository->getState()->getAndForget('edit.data')
                    ?: $this->orm->extractEntity($item)
            )
            ->fill(compact('type'));

        $this->prepareMetadata($app, $view);

        return compact('form', 'id', 'item', 'typeClass', 'typeInstance');
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
                $this->trans('unicorn.title.edit', title: $this->trans('luna.shipping.title'))
            );
    }
}

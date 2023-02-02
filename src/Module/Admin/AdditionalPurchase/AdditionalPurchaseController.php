<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Module\Admin\AdditionalPurchase;

use Lyrasoft\ShopGo\Entity\Product;
use Lyrasoft\ShopGo\Module\Admin\AdditionalPurchase\Form\EditForm;
use Lyrasoft\ShopGo\Repository\AdditionalPurchaseRepository;
use Unicorn\Controller\CrudController;
use Unicorn\Controller\GridController;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Attributes\Controller;
use Windwalker\Core\Form\FormFactory;
use Windwalker\Core\Router\Navigator;
use Windwalker\DI\Attributes\Autowire;
use Windwalker\ORM\ORM;

/**
 * The AdditionalPurchaseController class.
 */
#[Controller()]
class AdditionalPurchaseController
{
    public function save(
        AppContext $app,
        CrudController $controller,
        Navigator $nav,
        FormFactory $formFactory,
        ORM $orm,
        #[Autowire] AdditionalPurchaseRepository $repository,
    ): mixed {
        $task = $app->input('task');

        if ($task === 'switch_product') {
            $repository->getState()->remember('edit.data', $app->input('item'));

            return $nav->back();
        }

        $productId = $app->input('item')['attach_product_id'] ?? 0;
        $product = $orm->findOne(Product::class, $productId);

        $form = $formFactory->create(EditForm::class, product: $product);

        $uri = $app->call([$controller, 'save'], compact('repository', 'form'));

        switch ($app->input('task')) {
            case 'save2close':
                return $nav->to('additional_purchase_list');

            case 'save2new':
                return $nav->to('additional_purchase_edit')->var('new', 1);

            case 'save2copy':
                $controller->rememberForClone($app, $repository);
                return $nav->self($nav::WITHOUT_VARS)->var('new', 1);

            default:
                return $uri;
        }
    }

    public function delete(
        AppContext $app,
        #[Autowire] AdditionalPurchaseRepository $repository,
        CrudController $controller
    ): mixed {
        return $app->call([$controller, 'delete'], compact('repository'));
    }

    public function filter(
        AppContext $app,
        #[Autowire] AdditionalPurchaseRepository $repository,
        GridController $controller
    ): mixed {
        return $app->call([$controller, 'filter'], compact('repository'));
    }

    public function batch(
        AppContext $app,
        #[Autowire] AdditionalPurchaseRepository $repository,
        GridController $controller
    ): mixed {
        $task = $app->input('task');
        $data = match ($task) {
            'publish' => ['state' => 1],
            'unpublish' => ['state' => 0],
            default => null
        };

        return $app->call([$controller, 'batch'], compact('repository', 'data'));
    }

    public function copy(
        AppContext $app,
        #[Autowire] AdditionalPurchaseRepository $repository,
        GridController $controller
    ): mixed {
        return $app->call([$controller, 'copy'], compact('repository'));
    }
}

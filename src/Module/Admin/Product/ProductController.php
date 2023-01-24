<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Module\Admin\Product;

use App\Entity\ProductVariant;
use App\Module\Admin\Product\Form\EditForm;
use App\Repository\ProductRepository;
use Unicorn\Controller\CrudController;
use Unicorn\Controller\GridController;
use Unicorn\Repository\Event\PrepareSaveEvent;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Attributes\Controller;
use Windwalker\Core\Router\Navigator;
use Windwalker\DI\Attributes\Autowire;
use Windwalker\ORM\Event\AfterSaveEvent;
use Windwalker\ORM\Event\BeforeSaveEvent;

/**
 * The ProductController class.
 */
#[Controller()]
class ProductController
{
    public function save(
        AppContext $app,
        CrudController $controller,
        Navigator $nav,
        #[Autowire] ProductRepository $repository,
    ): mixed {
        $form = $app->make(EditForm::class);

        $controller->prepareSave(
            function (PrepareSaveEvent $event) use ($app) {
                $data = &$event->getData();
            }
        );

        $controller->beforeSave(
            function (BeforeSaveEvent $event) use ($app) {
                $data = &$event->getData();
            }
        );

        $controller->afterSave(
            function (AfterSaveEvent $event) use ($app) {
                $orm = $event->getORM();
                $data = $event->getData();
                $entity = $event->getEntity();

                $variantData = $app->input('item')['variant'];

                $mainVariant = $orm->findOneOrCreate(
                    ProductVariant::class,
                    ['product_id' => $data['id'], 'primary' => 1]
                );

                $mainVariant = $orm->hydrateEntity($variantData, $mainVariant);

                $orm->updateOne(ProductVariant::class, $mainVariant);
            }
        );

        $uri = $app->call([$controller, 'save'], compact('repository', 'form'));

        switch ($app->input('task')) {
            case 'save2close':
                return $nav->to('product_list');

            case 'save2new':
                return $nav->to('product_edit')->var('new', 1);

            case 'save2copy':
                $controller->rememberForClone($app, $repository);
                return $nav->self($nav::WITHOUT_VARS)->var('new', 1);

            default:
                return $uri;
        }
    }

    public function delete(
        AppContext $app,
        #[Autowire] ProductRepository $repository,
        CrudController $controller
    ): mixed {
        return $app->call([$controller, 'delete'], compact('repository'));
    }

    public function filter(
        AppContext $app,
        #[Autowire] ProductRepository $repository,
        GridController $controller
    ): mixed {
        return $app->call([$controller, 'filter'], compact('repository'));
    }

    public function batch(
        AppContext $app,
        #[Autowire] ProductRepository $repository,
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
        #[Autowire] ProductRepository $repository,
        GridController $controller
    ): mixed {
        return $app->call([$controller, 'copy'], compact('repository'));
    }
}

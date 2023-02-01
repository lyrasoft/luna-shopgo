<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyraoft\ShopGo\Module\Admin\ProductAttribute;

use Lyraoft\ShopGo\Entity\ShopCategoryMap;
use Lyraoft\ShopGo\Module\Admin\ProductAttribute\Form\EditForm;
use Lyraoft\ShopGo\Repository\ProductAttributeRepository;
use Unicorn\Controller\CrudController;
use Unicorn\Controller\GridController;
use Unicorn\Repository\Event\PrepareSaveEvent;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Attributes\Controller;
use Windwalker\Core\Router\Navigator;
use Windwalker\DI\Attributes\Autowire;
use Windwalker\ORM\Event\AfterSaveEvent;

/**
 * The ProductAttributeController class.
 */
#[Controller()]
class ProductAttributeController
{
    public function save(
        AppContext $app,
        CrudController $controller,
        Navigator $nav,
        #[Autowire] ProductAttributeRepository $repository,
    ): mixed {
        $form = $app->make(EditForm::class);

        $controller->prepareSave(
            function (PrepareSaveEvent $event) use ($app) {
                $data = &$event->getData();

                $options = $app->input('options');

                if ($options !== null) {
                    foreach ($options as &$option) {
                        $option['is_default'] = (bool) $option['is_default'];
                    }

                    unset($option);

                    $data['options'] = array_values($options);
                }
            }
        );

        $controller->afterSave(
            function (AfterSaveEvent $event) use ($app) {
                $orm = $event->getORM();
                $data = $event->getData();
                $categories = $app->input('item')['categories'] ?? [];

                $maps = [];

                foreach ($categories as $categoryId) {
                    $map = new ShopCategoryMap();
                    $map->setTargetId((int) $data['id']);
                    $map->setCategoryId((int) $categoryId);
                    $map->setType('attribute');

                    $maps[] = $map;
                }

                $orm->sync(
                    ShopCategoryMap::class,
                    $maps,
                    ['type' => 'attribute', 'target_id' => $data['id']],
                    ['type', 'category_id']
                );
            }
        );

        $uri = $app->call([$controller, 'save'], compact('repository', 'form'));

        switch ($app->input('task')) {
            case 'save2close':
                return $nav->to('product_attribute_list');

            case 'save2new':
                return $nav->to('product_attribute_edit')->var('new', 1);

            case 'save2copy':
                $controller->rememberForClone($app, $repository);
                return $nav->self($nav::WITHOUT_VARS)->var('new', 1);

            default:
                return $uri;
        }
    }

    public function delete(
        AppContext $app,
        #[Autowire] ProductAttributeRepository $repository,
        CrudController $controller
    ): mixed {
        return $app->call([$controller, 'delete'], compact('repository'));
    }

    public function filter(
        AppContext $app,
        #[Autowire] ProductAttributeRepository $repository,
        GridController $controller
    ): mixed {
        return $app->call([$controller, 'filter'], compact('repository'));
    }

    public function batch(
        AppContext $app,
        #[Autowire] ProductAttributeRepository $repository,
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
        #[Autowire] ProductAttributeRepository $repository,
        GridController $controller
    ): mixed {
        return $app->call([$controller, 'copy'], compact('repository'));
    }
}

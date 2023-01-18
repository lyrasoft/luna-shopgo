<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Module\Admin\ProductAttributeGroup;

use App\Entity\ShopCategoryMap;
use App\Module\Admin\ProductAttributeGroup\Form\EditForm;
use App\Repository\ProductAttributeGroupRepository;
use Unicorn\Controller\CrudController;
use Unicorn\Controller\GridController;
use Unicorn\Repository\Event\PrepareSaveEvent;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Attributes\Controller;
use Windwalker\Core\Router\Navigator;
use Windwalker\DI\Attributes\Autowire;
use Windwalker\ORM\Event\AfterSaveEvent;
use Windwalker\ORM\NestedSetMapper;

/**
 * The ProductAttributeGroupController class.
 */
#[Controller()]
class ProductAttributeGroupController
{
    public function save(
        AppContext $app,
        CrudController $controller,
        Navigator $nav,
        #[Autowire] ProductAttributeGroupRepository $repository,
    ): mixed {
        $form = $app->make(EditForm::class);

        $controller->prepareSave(
            function (PrepareSaveEvent $event) use ($repository, $app) {
                $data = &$event->getData();
                /** @var NestedSetMapper $mapper */
                $mapper = $repository->getEntityMapper();

                $data['parent_id'] = $mapper->getRoot()?->getPrimaryKeyValue() ?: 1;
                $data['type'] = 'attribute';
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
                    $map->setType('attribute_group');

                    $maps[] = $map;
                }

                $orm->sync(
                    ShopCategoryMap::class,
                    $maps,
                    ['type' => 'attribute_group', 'target_id' => $data['id']],
                    ['type', 'category_id']
                );
            }
        );

        $uri = $app->call([$controller, 'save'], compact('repository', 'form'));

        switch ($app->input('task')) {
            case 'save2close':
                return $nav->to('product_attribute_group_list');

            case 'save2new':
                return $nav->to('product_attribute_group_edit')->var('new', 1);

            case 'save2copy':
                $controller->rememberForClone($app, $repository);
                return $nav->self($nav::WITHOUT_VARS)->var('new', 1);

            default:
                return $uri;
        }
    }

    public function delete(
        AppContext $app,
        #[Autowire] ProductAttributeGroupRepository $repository,
        CrudController $controller
    ): mixed {
        return $app->call([$controller, 'delete'], compact('repository'));
    }

    public function filter(
        AppContext $app,
        #[Autowire] ProductAttributeGroupRepository $repository,
        GridController $controller
    ): mixed {
        return $app->call([$controller, 'filter'], compact('repository'));
    }

    public function batch(
        AppContext $app,
        #[Autowire] ProductAttributeGroupRepository $repository,
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
        #[Autowire] ProductAttributeGroupRepository $repository,
        GridController $controller
    ): mixed {
        return $app->call([$controller, 'copy'], compact('repository'));
    }
}

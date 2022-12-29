<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Module\Admin\Manufacturer;

use App\Entity\Manufacturer;
use App\Module\Admin\Manufacturer\Form\EditForm;
use App\Repository\ManufacturerRepository;
use Lyrasoft\Luna\Services\TagService;
use Unicorn\Controller\CrudController;
use Unicorn\Controller\GridController;
use Unicorn\Upload\FileUploadManager;
use Unicorn\Upload\FileUploadService;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Attributes\Controller;
use Windwalker\Core\Router\Navigator;
use Windwalker\DI\Attributes\Autowire;
use Windwalker\DI\Attributes\Service;
use Windwalker\ORM\Event\AfterSaveEvent;
use Windwalker\ORM\Event\BeforeSaveEvent;

/**
 * The ManufacturerController class.
 */
#[Controller()]
class ManufacturerController
{
    public function save(
        AppContext $app,
        CrudController $controller,
        Navigator $nav,
        #[Autowire] ManufacturerRepository $repository,
        TagService $tagService,
        #[Service(FileUploadManager::class, 'image')]
        FileUploadService $fileUploadService
    ): mixed {
        $form = $app->make(EditForm::class);

        $controller->beforeSave(
            function (BeforeSaveEvent $event) use ($app) {
                $data = $event->getData();

                $data['meta'] = [
                    'title' => $data['meta_title'],
                    'description' => $data['meta_description'],
                    'keywords' => $data['meta_keywords'],
                ];

                $event->setData($data);
            }
        );

        $controller->afterSave(
            function (AfterSaveEvent $event) use ($fileUploadService, $tagService, $repository, $app) {
                $data = $event->getData();

                $data['image'] = $fileUploadService->handleFileIfUploaded(
                    $app->file('item')['image'] ?? null,
                    'images/manufacturer/cover-' . md5((string) $data['id']) . '.{ext}'
                )?->getUri(true) ?? $data['image'];

                $repository->save($data);

                /** @var Manufacturer $entity */
                $entity = $event->getEntity();

                $tagService->flushTagMapsFromInput(
                    'manufacturer',
                    $entity->getId(),
                    (array) ($app->input('item')['tags'] ?? [])
                );
            }
        );

        $uri = $app->call([$controller, 'save'], compact('repository', 'form'));

        switch ($app->input('task')) {
            case 'save2close':
                return $nav->to('manufacturer_list');

            case 'save2new':
                return $nav->to('manufacturer_edit')->var('new', 1);

            case 'save2copy':
                $controller->rememberForClone($app, $repository);

                return $nav->self($nav::WITHOUT_VARS)->var('new', 1);

            default:
                return $uri;
        }
    }

    public function delete(
        AppContext $app,
        #[Autowire] ManufacturerRepository $repository,
        CrudController $controller
    ): mixed {
        return $app->call([$controller, 'delete'], compact('repository'));
    }

    public function filter(
        AppContext $app,
        #[Autowire] ManufacturerRepository $repository,
        GridController $controller
    ): mixed {
        return $app->call([$controller, 'filter'], compact('repository'));
    }

    public function batch(
        AppContext $app,
        #[Autowire] ManufacturerRepository $repository,
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
        #[Autowire] ManufacturerRepository $repository,
        GridController $controller
    ): mixed {
        return $app->call([$controller, 'copy'], compact('repository'));
    }
}

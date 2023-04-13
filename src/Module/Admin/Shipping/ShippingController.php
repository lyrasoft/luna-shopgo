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
use Unicorn\Controller\CrudController;
use Unicorn\Controller\GridController;
use Unicorn\Upload\FileUploadManager;
use Unicorn\Upload\FileUploadService;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Attributes\Controller;
use Windwalker\Core\Form\FormFactory;
use Windwalker\Core\Router\Navigator;
use Windwalker\DI\Attributes\Autowire;
use Windwalker\DI\Attributes\Service;
use Windwalker\ORM\Event\AfterSaveEvent;
use Windwalker\ORM\ORM;

/**
 * The ShippingController class.
 */
#[Controller()]
class ShippingController
{
    public function save(
        AppContext $app,
        CrudController $controller,
        Navigator $nav,
        #[Autowire] ShippingRepository $repository,
        #[Service(FileUploadManager::class, 'image')]
        FileUploadService $fileUploadService,
        ShippingService $shippingService,
        FormFactory $formFactory,
    ): mixed {
        $item = $app->input('item');
        $typeInstance = $shippingService->createTypeInstance(
            $item['type'] ?? 'basic',
            $app->service(ORM::class)->toEntity(Shipping::class, $item)
        );

        $form = $formFactory->create(EditForm::class)
            ->defineFormFields($typeInstance);

        $controller->afterSave(
            function (AfterSaveEvent $event) use ($repository, $app, $fileUploadService) {
                $data = $event->getData();

                $data['image'] = $fileUploadService->handleFileIfUploaded(
                    $app->file('item')['image'] ?? null,
                    'images/shopgo/shipping/' . md5((string) $data['id']) . '.{ext}'
                )?->getUri(true) ?? $data['image'];

                $repository->save($data);
            }
        );

        $uri = $app->call([$controller, 'save'], compact('repository', 'form'));

        switch ($app->input('task')) {
            case 'save2close':
                return $nav->to('shipping_list');

            case 'save2new':
                return $nav->to('shipping_edit')->var('new', 1);

            case 'save2copy':
                $controller->rememberForClone($app, $repository);
                return $nav->self($nav::WITHOUT_VARS)->var('new', 1);

            default:
                return $uri;
        }
    }

    public function delete(
        AppContext $app,
        #[Autowire] ShippingRepository $repository,
        CrudController $controller
    ): mixed {
        return $app->call([$controller, 'delete'], compact('repository'));
    }

    public function filter(
        AppContext $app,
        #[Autowire] ShippingRepository $repository,
        GridController $controller
    ): mixed {
        return $app->call([$controller, 'filter'], compact('repository'));
    }

    public function batch(
        AppContext $app,
        #[Autowire] ShippingRepository $repository,
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
        #[Autowire] ShippingRepository $repository,
        GridController $controller
    ): mixed {
        return $app->call([$controller, 'copy'], compact('repository'));
    }
}

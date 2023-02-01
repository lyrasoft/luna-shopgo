<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Module\Admin\Location;

use Lyrasoft\ShopGo\Entity\Location;
use Lyrasoft\ShopGo\Module\Admin\Location\Form\EditForm;
use Lyrasoft\ShopGo\Repository\LocationRepository;
use Unicorn\Controller\CrudController;
use Unicorn\Controller\GridController;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Attributes\Controller;
use Windwalker\Core\Attributes\JsonApi;
use Windwalker\Core\Router\Navigator;
use Windwalker\DI\Attributes\Autowire;
use Windwalker\ORM\ORM;
use Windwalker\Query\Query;

/**
 * The LocationController class.
 */
#[Controller()]
class LocationController
{
    public function save(
        AppContext $app,
        CrudController $controller,
        Navigator $nav,
        #[Autowire] LocationRepository $repository,
    ): mixed {
        $form = $app->make(EditForm::class);

        $uri = $app->call([$controller, 'save'], compact('repository', 'form'));

        switch ($app->input('task')) {
            case 'save2close':
                return $nav->to('location_list');

            case 'save2new':
                return $nav->to('location_edit')->var('new', 1);

            case 'save2copy':
                $controller->rememberForClone($app, $repository);
                return $nav->self($nav::WITHOUT_VARS)->var('new', 1);

            default:
                return $uri;
        }
    }

    public function delete(
        AppContext $app,
        #[Autowire] LocationRepository $repository,
        CrudController $controller
    ): mixed {
        return $app->call([$controller, 'delete'], compact('repository'));
    }

    public function filter(
        AppContext $app,
        #[Autowire] LocationRepository $repository,
        GridController $controller
    ): mixed {
        return $app->call([$controller, 'filter'], compact('repository'));
    }

    public function batch(
        AppContext $app,
        #[Autowire] LocationRepository $repository,
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
        #[Autowire] LocationRepository $repository,
        GridController $controller
    ): mixed {
        return $app->call([$controller, 'copy'], compact('repository'));
    }

    #[JsonApi]
    public function ajax(AppContext $app): mixed
    {
        $task = $app->input('task');

        return $app->call([$this, $task]);
    }

    /**
     * @param  AppContext  $app
     * @param  ORM         $orm
     *
     * @return  array<Location>
     */
    public function parentList(AppContext $app, ORM $orm): array
    {
        $value = $app->input('value') ?? 1;
        $self = $app->input('self');

        if ($value <= 0) {
            $value = 1;
        }

        $query = $orm->from(Location::class)
            ->where('parent_id', (int) $value)
            ->tapIf(
                (bool) $self,
                fn(Query $query) => $query->where('id', '!=', $self)
            )
            ->order('lft', 'ASC');

        $items = [];

        /** @var Location $item */
        foreach ($query->getIterator(Location::class) as $item) {
            $items[] = [
                'title' => $item->getTitle(),
                'id' => $item->getId(),
            ];
        }

        return $items;
    }
}

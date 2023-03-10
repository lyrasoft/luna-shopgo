<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Module\Front\Address;

use Lyrasoft\Luna\User\UserService;
use Lyrasoft\ShopGo\Entity\Address;
use Lyrasoft\ShopGo\Entity\Location;
use Lyrasoft\ShopGo\Enum\LocationType;
use Lyrasoft\ShopGo\Service\AddressService;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Attributes\Controller;
use Windwalker\Data\Collection;
use Windwalker\ORM\NestedSetMapper;
use Windwalker\ORM\ORM;
use Windwalker\Query\Query;

/**
 * The AddressController class.
 */
#[Controller(
    config: __DIR__ . '/address.config.php'
)]
class AddressController
{
    public function ajax(AppContext $app): mixed
    {
        $task = $app->input('task');

        return $app->call([$this, $task]);
    }

    public function getCountries(ORM $orm): Collection
    {
        return $orm->from(Location::class)
            ->where('state', 1)
            ->where('type', LocationType::COUNTRY())
            ->order('lft', 'ASC')
            ->all(Location::class);
    }

    public function locationOptions(AppContext $app, ORM $orm): array
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

    public function myAddresses(UserService $userService, AddressService $addressService): iterable
    {
        $user = $userService->getUser();

        return $addressService->getUserAddresses($user);
    }
}

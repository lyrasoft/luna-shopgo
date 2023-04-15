<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Service;

use Lyrasoft\Luna\Access\AccessService;
use Lyrasoft\Luna\Entity\User;
use Lyrasoft\Luna\Entity\UserRole;
use Lyrasoft\Luna\Entity\UserRoleMap;
use Lyrasoft\Luna\Repository\UserRepository;
use Windwalker\Data\Collection;
use Windwalker\DI\Attributes\Autowire;
use Windwalker\Query\Query;

use function Windwalker\collect;

/**
 * The MailNotifyService class.
 */
class MailNotifyService
{
    public const ACL_ORDER_NOTIFY_ACTION = 'shopgo.order.notify';

    public function __construct(
        protected AccessService $accessService,
        #[Autowire]
        protected UserRepository $userRepository
    ) {
        //
    }

    public function getAdminOrderNotifyReceivers(): Collection
    {
        return $this->getAdminReceivers(static::ACL_ORDER_NOTIFY_ACTION);
    }

    /**
     * @param  string  $accessAction
     *
     * @return  Collection<User>
     */
    public function getAdminReceivers(string $accessAction): Collection
    {
        $adminRoles = $this->accessService->getRolesAllowAction(AccessService::ADMIN_ACCESS_ACTION);
        $notifyRoles = collect($adminRoles)
            ->filter(
                function (UserRole $role) use ($accessAction) {
                    return $this->accessService->checkRoleAllowAction(
                        $role,
                        $accessAction
                    );
                }
            )
            ->column('id')
            ->dump();

        if ($notifyRoles === []) {
            return collect();
        }

        return $this->userRepository->getListSelector()
            ->where('user.receive_mail', 1)
            ->where('user.enabled', 1)
            ->where('user.verified', 1)
            ->modifyQuery(
                fn(Query $query) => $query->where(
                    $query->expr(
                        'EXISTS()',
                        $query->createSubQuery()
                            ->select('*')
                            ->from(UserRoleMap::class)
                            ->whereRaw('user_id = user.id')
                            ->whereRaw('role_id IN(%r)', implode(',', $query->quote($notifyRoles)))
                    )
                )
            )
            ->all(User::class);
    }
}

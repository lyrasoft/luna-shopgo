<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Shipping;

use Lyrasoft\ShopGo\Entity\Location;
use Lyrasoft\ShopGo\Entity\Shipping;
use Lyrasoft\ShopGo\Repository\ShippingRepository;
use Lyrasoft\ShopGo\ShopGoPackage;
use Windwalker\Core\Application\ApplicationInterface;
use Windwalker\DI\Attributes\Autowire;
use Windwalker\ORM\ORM;
use Windwalker\Utilities\Cache\InstanceCacheTrait;

/**
 * The ShippingService class.
 */
class ShippingService
{
    use InstanceCacheTrait;

    public function __construct(
        protected ApplicationInterface $app,
        protected ShopGoPackage $shopGo,
        protected ORM $orm,
        #[Autowire] protected ShippingRepository $repository
    ) {
    }

    public function getShippings(Location $location, array $variantIds): iterable
    {
        return $this->repository->getAvailableListSelector()
            ->all(Shipping::class);
    }

    public function createTypeInstance(string $type, ?Shipping $data = null): ?AbstractShipping
    {
        $typeClass = $this->getTypeClass($type);

        if (!$typeClass) {
            return null;
        }

        $typeInstance = $this->app->make($typeClass);

        /** @var AbstractShipping $typeInstance */

        if ($data) {
            $typeInstance->setData($data);
        }

        return $typeInstance;
    }

    /**
     * getWidgetTypes
     *
     * @return  array<class-string<AbstractShipping>>
     */
    public function getTypes(): array
    {
        return $this->once(
            'types',
            function () {
                $typeClasses = $this->shopGo->config('shipping.types') ?? [];

                $types = [];

                /** @var AbstractShipping $typeClass */
                foreach ($typeClasses as $typeName => $typeClass) {
                    if ($typeClass && is_subclass_of($typeClass, AbstractShipping::class, true)) {
                        $types[$typeName] = $typeClass;
                        $typeClass::setType($typeName);
                    }
                }

                return $types;
            }
        );
    }

    public function getTypeClass(string $type): ?string
    {
        return $this->getTypes()[$type] ?? null;
    }
}

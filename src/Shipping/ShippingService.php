<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Shipping;

use Lyrasoft\Luna\Entity\Category;
use Lyrasoft\Luna\Entity\TagMap;
use Lyrasoft\ShopGo\Entity\Location;
use Lyrasoft\ShopGo\Entity\Product;
use Lyrasoft\ShopGo\Entity\Shipping;
use Lyrasoft\ShopGo\Repository\ShippingRepository;
use Lyrasoft\ShopGo\ShopGoPackage;
use Windwalker\Core\Application\ApplicationInterface;
use Windwalker\Data\Collection;
use Windwalker\DI\Attributes\Autowire;
use Windwalker\ORM\ORM;
use Windwalker\Query\Query;
use Windwalker\Utilities\Cache\InstanceCacheTrait;
use Windwalker\Utilities\TypeCast;

use function Windwalker\collect;

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

    /**
     * @param  Location  $location
     * @param  array<Product>  $products
     *
     * @return  Collection<Shipping>
     */
    public function getShippings(Location $location, array $products): Collection
    {
        $shippings = $this->repository->getAvailableListSelector()
            ->orWhere(
                function (Query $query) use ($location) {
                    $query->where('shipping.location_category_id', 0);
                    $query->whereExists(
                        fn(Query $query) => $query->from(Location::class)
                            ->leftJoin(Category::class)
                            ->whereRaw('category.id = shipping.location_category_id')
                            ->whereRaw('location.lft <= %a', $location->getLft())
                            ->whereRaw('location.rgt >= %a', $location->getRgt())
                    );
                }
            )
            ->orWhere(
                function (Query $query) use ($location) {
                    $query->where('shipping.location_id', 0);
                    $query->whereExists(
                        fn(Query $query) => $query->from(Location::class)
                            ->whereRaw('location.id = shipping.location_id')
                            ->whereRaw('location.lft <= %a', $location->getLft())
                            ->whereRaw('location.rgt >= %a', $location->getRgt())
                    );
                }
            )
            ->all(Shipping::class);

        if (count($shippings) === 0) {
            return $shippings;
        }

        // Filter by product shippings
        $productShippingIds = collect();

        /** @var Product $product */
        foreach ($products as $product) {
            if (!$product->getShippings()) {
                continue;
            }

            if ($productShippingIds->count() === 0) {
                $productShippingIds = collect($product->getShippings());
            } else {
                $productShippingIds = array_intersect($productShippingIds, $product->getShippings());
            }
        }

        $productShippingIds = $productShippingIds->map('intval');

        if ($productShippingIds->count()) {
            $shippings = $shippings->filter(
                fn(Shipping $shipping) => $productShippingIds->contains($shipping->getId())
            );
        }

        // Filter by tags
        $productIds = array_column($products, 'id');

        $tagMapsSet = $this->orm->from(TagMap::class, 'map')
            ->where('map.target_id', $productIds ?: [0])
            ->where('map.type', 'product')
            ->all(TagMap::class)
            ->groupBy('targetId');

        // Let's product filter tags
        $shippings = $shippings->filter(
            function (Shipping $shipping) use ($products, $tagMapsSet) {
                if (
                    $shipping->getUnallowTags() === []
                    && $shipping->getAllowTags() === []
                ) {
                    return true;
                }

                // Allow Tags should default TRUE if no selected
                $allow = $shipping->getAllowTags() === [];

                // Unallow Tags always default FALSE, only TRUE if matched.
                $disallow = false;

                foreach ($products as $product) {
                    $tagMaps = $tagMapsSet[$product->getId()] ?? collect();
                    $tagIds = $tagMaps->column('tagId')->values();

                    $allow = $allow || $tagIds->intersect($shipping->getAllowTags())->count() > 0;
                    $disallow = $disallow || $tagIds->intersect($shipping->getUnallowTags())->count() > 0;
                }

                // Disallow priority higher, if TRUE means ignore this shipping.
                if ($disallow) {
                    return false;
                }

                // Final, we return allow matched or not.
                return $allow;
            }
        );

        return $shippings->values();
    }

    /**
     * @param  iterable  $locationCategoryIds
     * @param  iterable  $locationIds
     *
     * @return  Collection<Location>
     */
    public function getFlatLocations(iterable $locationCategoryIds, iterable $locationIds): Collection
    {
        $locationCategoryIds = TypeCast::toArray($locationCategoryIds);
        $locationIds = TypeCast::toArray($locationIds);

        return $this->orm->select('location.*')
            ->from(Location::class)
            ->leftJoin(Category::class)
            ->orWhere(
                function (Query $query) use ($locationIds, $locationCategoryIds) {
                    $query->where('location.id', $locationIds ?: [0]);
                    $query->where('category.id', $locationCategoryIds ?: [0]);
                }
            )
            ->all(Location::class);
    }

    public function createTypeInstance(string|Shipping $type, ?Shipping $data = null): ?AbstractShipping
    {
        if ($type instanceof Shipping) {
            $data = $type;
            $type = $type->getType();
        }

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

    public function getInstanceById(int|string $id): AbstractShipping
    {
        $shipping = $this->once('shipping.' . $id, fn () => $this->orm->mustFindOne(Shipping::class, $id));

        return $this->createTypeInstance($shipping);
    }
}

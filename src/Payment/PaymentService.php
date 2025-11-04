<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Payment;

use Lyrasoft\Luna\Entity\Category;
use Lyrasoft\ShopGo\Entity\Location;
use Lyrasoft\ShopGo\Entity\Payment;
use Lyrasoft\ShopGo\Entity\Shipping;
use Lyrasoft\ShopGo\Repository\PaymentRepository;
use Lyrasoft\ShopGo\ShopGoPackage;
use Unicorn\Selector\ListSelector;
use Windwalker\Core\Application\ApplicationInterface;
use Windwalker\Data\Collection;
use Windwalker\DI\Attributes\Autowire;
use Windwalker\ORM\ORM;
use Windwalker\Query\Query;
use Windwalker\Utilities\Cache\InstanceCacheTrait;

use function Windwalker\collect;

/**
 * The PaymentService class.
 */
class PaymentService
{
    use InstanceCacheTrait;

    public function __construct(
        protected ApplicationInterface $app,
        protected ORM $orm,
        protected ShopGoPackage $shopGo,
        #[Autowire]
        protected PaymentRepository $repository
    ) {
    }

    /**
     * @param  Location  $location
     * @param  Shipping  $shipping
     *
     * @return  Collection<Payment>
     */
    public function getPayments(Location $location, Shipping $shipping): Collection
    {
        return $this->once(
            'payments::' . $location->id . '::' . $shipping->id,
            function () use ($location, $shipping) {
                $paymentIds = $shipping->payments;

                $defines = $this->shopGo->config('shipping.defines');

                if ($defines instanceof \Closure) {
                    $defines = $this->app->call(
                        $defines,
                        [
                            'location' => $location,
                            Location::class => $location,
                            'shipping' => $shipping,
                            Shipping::class => $shipping,
                        ]
                    );
                }

                $defines = collect($defines)
                    ->mapWithKeys(
                        function ($instance, $key) {
                            if (!$instance->id) {
                                $instance->id = $instance->alias ?: $key;
                            }

                            yield $key => $instance;
                        }
                    );

                return $defines->merge(
                    $this->repository->getAvailableListSelector()
                        ->orWhere(
                            function (Query $query) use ($location) {
                                $query->where('payment.location_category_id', 0);
                                $query->whereExists(
                                    fn(Query $query) => $query->from(Location::class)
                                        ->leftJoin(Category::class)
                                        ->whereRaw('category.id = payment.location_category_id')
                                        ->whereRaw('location.lft <= %a', $location->lft)
                                        ->whereRaw('location.rgt >= %a', $location->rgt)
                                );
                            }
                        )
                        ->orWhere(
                            function (Query $query) use ($location) {
                                $query->where('payment.location_id', 0);
                                $query->whereExists(
                                    fn(Query $query) => $query->from(Location::class)
                                        ->whereRaw('location.id = payment.location_id')
                                        ->whereRaw('location.lft <= %a', $location->lft)
                                        ->whereRaw('location.rgt >= %a', $location->rgt)
                                );
                            }
                        )
                        ->tapIf(
                            $paymentIds !== [],
                            fn (ListSelector $query) => $query->where('payment.id', $paymentIds)
                        )
                        ->all(Payment::class)
                );
            }
        );
    }

    public function createTypeInstance(string|Payment $type, ?Payment $data = null): ?AbstractPayment
    {
        if ($type instanceof Payment) {
            $data = $type;
            $type = $type->type;
        }

        $typeClass = $this->getTypeClass($type);

        if (!$typeClass) {
            return null;
        }

        $typeInstance = $this->app->make($typeClass);

        /** @var AbstractPayment $typeInstance */

        if ($data) {
            $typeInstance->setData($data);
        }

        return $typeInstance;
    }

    /**
     * getWidgetTypes
     *
     * @return  array<class-string<AbstractPayment>>
     */
    public function getTypes(): array
    {
        return $this->once(
            'types',
            function () {
                $typeClasses = $this->shopGo->config('payment.types') ?? [];

                $types = [];

                /** @var AbstractPayment $typeClass */
                foreach ($typeClasses as $typeName => $typeClass) {
                    if ($typeClass && is_subclass_of($typeClass, AbstractPayment::class, true)) {
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

    public function getInstanceById(int|string $id): AbstractPayment
    {
        $payment = $this->once('payment.' . $id, fn () => $this->orm->mustFindOne(Payment::class, $id));

        return $this->createTypeInstance($payment);
    }
}

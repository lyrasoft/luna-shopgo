<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Entity;

use Lyrasoft\Luna\Attributes\Author;
use Lyrasoft\Luna\Attributes\Modifier;
use Lyrasoft\ShopGo\Data\InvoiceData;
use Lyrasoft\ShopGo\Data\PaymentData;
use Lyrasoft\ShopGo\Data\PaymentInfo;
use Lyrasoft\ShopGo\Data\ShippingData;
use Lyrasoft\ShopGo\Data\ShippingHistoryCollection;
use Lyrasoft\ShopGo\Data\ShippingInfo;
use Lyrasoft\ShopGo\Enum\InvoiceType;
use Lyrasoft\ShopGo\Service\OrderStateService;
use Lyrasoft\ShopGo\Workflow\OrderStateWorkflow;
use Windwalker\Core\DateTime\Chronos;
use Windwalker\Data\Collection;
use Windwalker\DI\Attributes\Autowire;
use Windwalker\ORM\Attributes\AutoIncrement;
use Windwalker\ORM\Attributes\Cast;
use Windwalker\ORM\Attributes\CastNullable;
use Windwalker\ORM\Attributes\Column;
use Windwalker\ORM\Attributes\CreatedTime;
use Windwalker\ORM\Attributes\CurrentTime;
use Windwalker\ORM\Attributes\EntitySetup;
use Windwalker\ORM\Attributes\ManyToOne;
use Windwalker\ORM\Attributes\OnDelete;
use Windwalker\ORM\Attributes\OneToMany;
use Windwalker\ORM\Attributes\OnUpdate;
use Windwalker\ORM\Attributes\PK;
use Windwalker\ORM\Attributes\Table;
use Windwalker\ORM\Attributes\TargetTo;
use Windwalker\ORM\Attributes\Watch;
use Windwalker\ORM\Cast\JsonCast;
use Windwalker\ORM\EntityInterface;
use Windwalker\ORM\EntityTrait;
use Windwalker\ORM\Event\BeforeSaveEvent;
use Windwalker\ORM\Event\WatchEvent;
use Windwalker\ORM\Metadata\EntityMetadata;
use Windwalker\ORM\Relation\Action;
use Windwalker\ORM\Relation\RelationCollection;

use function Windwalker\collect;

/**
 * The Order class.
 */
// phpcs:disable
// todo: remove this when phpcs supports 8.4
#[Table('orders', 'order')]
#[\AllowDynamicProperties]
class Order implements EntityInterface
{
    use EntityTrait;

    #[Column('id'), PK, AutoIncrement]
    public ?int $id = null;

    #[Column('user_id')]
    public int $userId = 0;

    #[Column('no')]
    public string $no = '';

    #[Column('total')]
    public float $total = 0.0;

    #[Column('rewards')]
    public float $rewards = 0.0;

    #[Column('invoice_type')]
    #[Cast(InvoiceType::class)]
    public InvoiceType $invoiceType {
        set(InvoiceType|string $value) => $this->invoiceType = InvoiceType::wrap($value);
        get => $this->invoiceType ??= InvoiceType::IDV;
    }

    #[Column('invoice_no')]
    public string $invoiceNo = '';

    #[Column('invoice_data')]
    #[Cast(JsonCast::class)]
    #[Cast(InvoiceData::class)]
    public InvoiceData $invoiceData {
        set(InvoiceData|array|null $value) => $this->invoiceData = InvoiceData::wrap($value);
        get => $this->invoiceData ??= new InvoiceData();
    }

    #[Column('state_id')]
    public int $stateId = 0;

    #[Column('state_text')]
    public string $stateText = '';

    /**
     * Payment ID or key name.
     *
     * @var string
     */
    #[Column('payment_id')]
    public string $paymentId = '';

    #[Column('payment_no')]
    public string $paymentNo = '';

    /**
     * User payment data
     *
     * @var PaymentData
     */
    #[Column('payment_data')]
    #[Cast(JsonCast::class)]
    #[Cast(PaymentData::class)]
    public PaymentData $paymentData {
        set(PaymentData|array|null $value) => $this->paymentData = PaymentData::wrap($value);
        get => $this->paymentData ??= new PaymentData();
    }

    /**
     * The payment API arguments
     *
     * @var array
     */
    #[Column('payment_args')]
    #[Cast(JsonCast::class)]
    public array $paymentArgs = [];

    /**
     * The pay info that payment gateway returned to site.
     *
     * @var PaymentInfo
     */
    #[Column('payment_info')]
    #[Cast(JsonCast::class)]
    #[Cast(PaymentInfo::class)]
    public PaymentInfo $paymentInfo {
        set(PaymentInfo|array|null $value) => $this->paymentInfo = PaymentInfo::wrap($value);
        get => $this->paymentInfo ??= new PaymentInfo();
    }

    /**
     * Shipping ID or key name.
     *
     * @var string
     */
    #[Column('shipping_id')]
    public string $shippingId = '';

    #[Column('shipping_no')]
    public string $shippingNo = '';

    #[Column('shipping_status')]
    public string $shippingStatus = '';

    /**
     * User shipping data.
     *
     * @var ShippingData
     */
    #[Column('shipping_data')]
    #[Cast(JsonCast::class)]
    #[Cast(ShippingData::class)]
    public ShippingData $shippingData {
        set(ShippingData|array|null $value) => $this->shippingData = ShippingData::wrap($value);
        get => $this->shippingData ??= new ShippingData();
    }

    /**
     * The arguments sent to shipping API.
     *
     * @var array
     */
    #[Column('shipping_args')]
    #[Cast(JsonCast::class)]
    public array $shippingArgs = [];

    /**
     * Thr shipping info returned from shipping services.
     *
     * @var ShippingInfo
     */
    #[Column('shipping_info')]
    #[Cast(JsonCast::class)]
    #[Cast(ShippingInfo::class)]
    public ShippingInfo $shippingInfo {
        set(ShippingInfo|array|null $value) => $this->shippingInfo = ShippingInfo::wrap($value);
        get => $this->shippingInfo ??= new ShippingInfo();
    }

    /**
     * The shipping histories
     *
     * @var ShippingHistoryCollection
     */
    #[Column('shipping_history')]
    #[Cast(JsonCast::class)]
    #[Cast(ShippingHistoryCollection::class)]
    public ShippingHistoryCollection $shippingHistory {
        set(ShippingHistoryCollection|array|null $value) => $this->shippingHistory = ShippingHistoryCollection::wrap($value);
        get => $this->shippingHistory ??= new ShippingHistoryCollection();
    }

    #[Column('note')]
    public string $note = '';

    #[Column('paid_at')]
    #[CastNullable(Chronos::class)]
    public ?Chronos $paidAt = null {
        set(\DateTimeInterface|string|null $value) => $this->paidAt = Chronos::tryWrap($value);
    }

    #[Column('shipped_at')]
    #[CastNullable(Chronos::class)]
    public ?Chronos $shippedAt = null {
        set(\DateTimeInterface|string|null $value) => $this->shippedAt = Chronos::tryWrap($value);
    }

    #[Column('returned_at')]
    #[CastNullable(Chronos::class)]
    public ?Chronos $returnedAt = null {
        set(\DateTimeInterface|string|null $value) => $this->returnedAt = Chronos::tryWrap($value);
    }

    #[Column('done_at')]
    #[CastNullable(Chronos::class)]
    public ?Chronos $doneAt = null {
        set(\DateTimeInterface|string|null $value) => $this->doneAt = Chronos::tryWrap($value);
    }

    #[Column('cancelled_at')]
    #[CastNullable(Chronos::class)]
    public ?Chronos $cancelledAt = null {
        set(\DateTimeInterface|string|null $value) => $this->cancelledAt = Chronos::tryWrap($value);
    }

    #[Column('rollback_at')]
    #[CastNullable(Chronos::class)]
    public ?Chronos $rollbackAt = null {
        set(\DateTimeInterface|string|null $value) => $this->rollbackAt = Chronos::tryWrap($value);
    }

    #[Column('expiry_on')]
    #[CastNullable(Chronos::class)]
    public ?Chronos $expiryOn = null {
        set(\DateTimeInterface|string|null $value) => $this->expiryOn = Chronos::tryWrap($value);
    }

    #[Column('search_index')]
    public string $searchIndex = '';

    #[Column('created')]
    #[CastNullable(Chronos::class)]
    #[CreatedTime]
    public ?Chronos $created = null {
        set(\DateTimeInterface|string|null $value) => $this->created = Chronos::tryWrap($value);
    }

    #[Column('modified')]
    #[CastNullable(Chronos::class)]
    #[CurrentTime]
    public ?Chronos $modified = null {
        set(\DateTimeInterface|string|null $value) => $this->modified = Chronos::tryWrap($value);
    }

    #[Column('created_by')]
    #[Author]
    public int $createdBy = 0;

    #[Column('modified_by')]
    #[Modifier]
    public int $modifiedBy = 0;

    #[Column('params')]
    #[Cast(JsonCast::class)]
    public array $params = [];

    #[
        ManyToOne,
        TargetTo(OrderState::class, state_id: 'id'),
        OnUpdate(Action::IGNORE),
        OnDelete(Action::IGNORE)
    ]
    public ?OrderState $state = null {
        get => $this->state ??= $this->fetchRelation('state');
        set(?OrderState $state) {
            $this->setState($state);
        }
    }

    #[
        ManyToOne,
        TargetTo(Payment::class, payment_id: 'id'),
        OnUpdate(Action::IGNORE),
        OnDelete(Action::IGNORE)
    ]
    public ?Payment $payment = null {
        get => $this->payment ??= $this->fetchRelation('payment');
        set(?Payment $payment) {
            $this->setPayment($payment);
        }
    }

    #[
        ManyToOne,
        TargetTo(Shipping::class, shipping_id: 'id'),
        OnUpdate(Action::IGNORE),
        OnDelete(Action::IGNORE)
    ]
    public ?Shipping $shipping = null {
        get => $this->shipping ??= $this->fetchRelation('shipping');
        set(?Shipping $shipping) {
            $this->setShipping($shipping);
        }
    }

    #[
        OneToMany,
        TargetTo(OrderTotal::class, id: 'order_id'),
        OnUpdate(Action::CASCADE),
        OnDelete(Action::CASCADE)
    ]
    public RelationCollection $totals {
        get => $this->totals ??= $this->fetchCollection('totals');
    }

    #[
        OneToMany,
        TargetTo(OrderItem::class, id: 'order_id'),
        OnUpdate(Action::CASCADE),
        OnDelete(Action::CASCADE)
    ]
    public RelationCollection $orderItems {
        get => $this->orderItems ??= $this->fetchCollection('orderItems');
    }

    #[EntitySetup]
    public static function setup(
        EntityMetadata $metadata,
        // #[Autowire] OrderStateWorkflow $workflow
    ): void {
        // $workflow->listen($metadata);
    }

    #[BeforeSaveEvent]
    public static function beforeSave(BeforeSaveEvent $event): void
    {
        $data = &$event->data;
        $orm = $event->orm;

        if ($data['state_id']) {
            $state = $orm->findOne(OrderState::class, $data['state_id']);

            $data['state_text'] = $state?->title ?: '';
        } else {
            $data['state_text'] = '';
        }

        $searchIndex = collect();

        $entity = $orm->toEntity(static::class, $data);
        $paymentData = $entity->paymentData;
        $shippingData = $entity->shippingData;
        $invoiceData = $entity->invoiceData;

        $searchIndex = $searchIndex->merge(
            array_values($paymentData->dump()),
            array_values($shippingData->dump()),
            array_values($invoiceData->dump()),
        );

        $data['search_index'] = $searchIndex->filter()->implode('|');
    }

    #[Watch('state_id')]
    public static function watchState(WatchEvent $event, OrderStateService $orderStateService): void
    {
        $orm = $event->orm;

        $orderStateService->handleStateChanged(
            $orm->toEntity(static::class, $event->data),
            (int) $event->oldValue,
            (int) $event->value,
            $orm->toEntity(static::class, $event->oldData)
        );
    }

    /**
     * @param  Payment|string|int  $payment
     *
     * @return  static  Return self to support chaining.
     */
    public function setPayment(Payment|string|int $payment): static
    {
        if ($payment instanceof Payment) {
            $payment = $payment->id;
        }

        $this->paymentId = $payment;

        return $this;
    }

    /**
     * @param  Shipping|string|int  $shipping
     *
     * @return  static  Return self to support chaining.
     */
    public function setShipping(Shipping|string|int $shipping): static
    {
        if ($shipping instanceof Shipping) {
            $shipping = $shipping->id;
        }

        $this->shippingId = (string) $shipping;

        return $this;
    }

    public function setState(OrderState|null $state): static
    {
        if ($state) {
            $this->stateId = $state->id;
            $this->stateText = $state->title;
        } else {
            $this->stateId = 0;
            $this->stateText = '';
        }

        return $this;
    }
}

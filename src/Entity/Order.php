<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    MIT
 */

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
use Windwalker\ORM\Cast\JsonCast;
use Windwalker\ORM\EntityInterface;
use Windwalker\ORM\EntityTrait;
use Windwalker\ORM\Event\BeforeSaveEvent;
use Windwalker\ORM\Metadata\EntityMetadata;
use Windwalker\ORM\Relation\Action;
use Windwalker\ORM\Relation\RelationCollection;

use function Windwalker\collect;

/**
 * The Order class.
 */
#[Table('orders', 'order')]
class Order implements EntityInterface
{
    use EntityTrait;

    #[Column('id'), PK, AutoIncrement]
    protected ?int $id = null;

    #[Column('user_id')]
    protected int $userId = 0;

    #[Column('no')]
    protected string $no = '';

    #[Column('total')]
    protected float $total = 0.0;

    #[Column('rewards')]
    protected float $rewards = 0.0;

    #[Column('invoice_type')]
    #[Cast(InvoiceType::class)]
    protected InvoiceType $invoiceType;

    #[Column('invoice_no')]
    protected string $invoiceNo = '';

    #[Column('invoice_data')]
    #[Cast(JsonCast::class)]
    #[Cast(InvoiceData::class)]
    protected InvoiceData $invoiceData;

    #[Column('state_id')]
    protected int $stateId = 0;

    #[Column('state_text')]
    protected string $stateText = '';

    /**
     * Payment ID or key name.
     *
     * @var string
     */
    #[Column('payment_id')]
    protected string $paymentId = '';

    #[Column('payment_no')]
    protected string $paymentNo = '';

    /**
     * User payment data
     *
     * @var PaymentData
     */
    #[Column('payment_data')]
    #[Cast(JsonCast::class)]
    #[Cast(PaymentData::class)]
    protected PaymentData $paymentData;

    /**
     * The payment API arguments
     *
     * @var array
     */
    #[Column('payment_args')]
    #[Cast(JsonCast::class)]
    protected array $paymentArgs = [];

    /**
     * The pay info that payment gateway returned to site.
     *
     * @var PaymentInfo
     */
    #[Column('payment_info')]
    #[Cast(JsonCast::class)]
    #[Cast(PaymentInfo::class)]
    protected PaymentInfo $paymentInfo;

    /**
     * Shipping ID or key name.
     *
     * @var string
     */
    #[Column('shipping_id')]
    protected string $shippingId = '';

    #[Column('shipping_no')]
    protected string $shippingNo = '';

    #[Column('shipping_status')]
    protected string $shippingStatus = '';

    /**
     * User shipping data.
     *
     * @var ShippingData
     */
    #[Column('shipping_data')]
    #[Cast(JsonCast::class)]
    #[Cast(ShippingData::class)]
    protected ShippingData $shippingData;

    /**
     * The arguments sent to shipping API.
     *
     * @var array
     */
    #[Column('shipping_args')]
    #[Cast(JsonCast::class)]
    protected array $shippingArgs = [];

    /**
     * Thr shipping info returned from shipping services.
     *
     * @var ShippingInfo
     */
    #[Column('shipping_info')]
    #[Cast(JsonCast::class)]
    #[Cast(ShippingInfo::class)]
    protected ShippingInfo $shippingInfo;

    /**
     * The shipping histories
     *
     * @var ShippingHistoryCollection
     */
    #[Column('shipping_history')]
    #[Cast(JsonCast::class)]
    #[Cast(ShippingHistoryCollection::class)]
    protected ShippingHistoryCollection $shippingHistory;

    #[Column('note')]
    protected string $note = '';

    #[Column('paid_at')]
    #[CastNullable(Chronos::class)]
    protected ?Chronos $paidAt = null;

    #[Column('shipped_at')]
    #[CastNullable(Chronos::class)]
    protected ?Chronos $shippedAt = null;

    #[Column('returned_at')]
    #[CastNullable(Chronos::class)]
    protected ?Chronos $returnedAt = null;

    #[Column('done_at')]
    #[CastNullable(Chronos::class)]
    protected ?Chronos $doneAt = null;

    #[Column('cancelled_at')]
    #[CastNullable(Chronos::class)]
    protected ?Chronos $cancelledAt = null;

    #[Column('rollback_at')]
    #[CastNullable(Chronos::class)]
    protected ?Chronos $rollbackAt = null;

    #[Column('expiry_on')]
    #[CastNullable(Chronos::class)]
    protected ?Chronos $expiryOn = null;

    #[Column('search_index')]
    protected string $searchIndex = '';

    #[Column('created')]
    #[CastNullable(Chronos::class)]
    #[CreatedTime]
    protected ?Chronos $created = null;

    #[Column('modified')]
    #[CastNullable(Chronos::class)]
    #[CurrentTime]
    protected ?Chronos $modified = null;

    #[Column('created_by')]
    #[Author]
    protected int $createdBy = 0;

    #[Column('modified_by')]
    #[Modifier]
    protected int $modifiedBy = 0;

    #[Column('params')]
    #[Cast(JsonCast::class)]
    protected array $params = [];

    #[
        ManyToOne,
        TargetTo(OrderState::class, state_id: 'id'),
        OnUpdate(Action::IGNORE),
        OnDelete(Action::IGNORE)
    ]
    protected ?OrderState $state = null;

    #[
        ManyToOne,
        TargetTo(Payment::class, payment_id: 'id'),
        OnUpdate(Action::IGNORE),
        OnDelete(Action::IGNORE)
    ]
    protected ?Payment $payment = null;

    #[
        ManyToOne,
        TargetTo(Shipping::class, shipping_id: 'id'),
        OnUpdate(Action::IGNORE),
        OnDelete(Action::IGNORE)
    ]
    protected ?Shipping $shipping = null;

    #[
        OneToMany,
        TargetTo(OrderTotal::class, id: 'order_id'),
        OnUpdate(Action::CASCADE),
        OnDelete(Action::CASCADE)
    ]
    protected ?RelationCollection $totals = null;

    #[
        OneToMany,
        TargetTo(OrderItem::class, id: 'order_id'),
        OnUpdate(Action::CASCADE),
        OnDelete(Action::CASCADE)
    ]
    protected RelationCollection|null $orderItems = null;

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
        $data = &$event->getData();
        $orm = $event->getORM();

        if ($data['state_id']) {
            $state = $orm->findOne(OrderState::class, $data['state_id']);

            $data['state_text'] = $state?->getTitle() ?: '';
        } else {
            $data['state_text'] = '';
        }

        $searchIndex = collect();

        $entity = $orm->toEntity(static::class, $data);
        $paymentData = $entity->getPaymentData();
        $shippingData = $entity->getShippingData();
        $invoiceData = $entity->getInvoiceData();

        $searchIndex = $searchIndex->merge(
            array_values($paymentData->dump()),
            array_values($shippingData->dump()),
            array_values($invoiceData->dump()),
        );

        $data['search_index'] = $searchIndex->filter()->implode('|');
    }

    /**
     * @return Payment|null
     */
    public function getPayment(): ?Payment
    {
        return $this->payment ??= $this->loadRelation('payment');
    }

    /**
     * @param  Payment|string|int  $payment
     *
     * @return  static  Return self to support chaining.
     */
    public function setPayment(Payment|string|int $payment): static
    {
        if ($payment instanceof Payment) {
            $payment = $payment->getId();
        }

        $this->paymentId = $payment;

        return $this;
    }

    /**
     * @return Shipping|null
     */
    public function getShipping(): ?Shipping
    {
        return $this->shipping ??= $this->loadRelation('shipping');
    }

    /**
     * @param  Shipping|string|int  $shipping
     *
     * @return  static  Return self to support chaining.
     */
    public function setShipping(Shipping|string|int $shipping): static
    {
        if ($shipping instanceof Shipping) {
            $shipping = $shipping->getId();
        }

        $this->shippingId = (string) $shipping;

        return $this;
    }

    /**
     * @return RelationCollection
     */
    public function getTotals(): RelationCollection
    {
        return $this->totals ??= $this->loadCollection('totals');
    }

    /**
     * @param  RelationCollection|null  $totals
     *
     * @return  static  Return self to support chaining.
     */
    public function setTotals(?RelationCollection $totals): static
    {
        $this->totals = $totals;

        return $this;
    }

    /**
     * @return RelationCollection
     */
    public function getOrderItems(): RelationCollection
    {
        return $this->orderItems ??= $this->loadCollection('orderItems');
    }

    /**
     * @param  Collection|null  $orderItems
     *
     * @return  static  Return self to support chaining.
     */
    public function setOrderItems(?Collection $orderItems): static
    {
        $this->orderItems = $orderItems;

        return $this;
    }

    /**
     * @return OrderState
     */
    public function getState(): OrderState
    {
        return $this->state ??= $this->loadRelation('state');
    }

    /**
     * @param  OrderState|null  $state
     *
     * @return  static  Return self to support chaining.
     */
    public function setState(OrderState|null $state): static
    {
        if ($state) {
            $this->stateId = $state->getId();
            $this->stateText = $state->getTitle();
        } else {
            $this->stateId = 0;
            $this->stateText = '';
        }

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    public function getNo(): string
    {
        return $this->no;
    }

    public function setNo(string $no): static
    {
        $this->no = $no;

        return $this;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function setTotal(float $total): static
    {
        $this->total = $total;

        return $this;
    }

    public function getRewards(): float
    {
        return $this->rewards;
    }

    public function setRewards(float $rewards): static
    {
        $this->rewards = $rewards;

        return $this;
    }

    public function getInvoiceType(): InvoiceType
    {
        return $this->invoiceType;
    }

    public function setInvoiceType(string|InvoiceType $invoiceType): static
    {
        $this->invoiceType = InvoiceType::wrap($invoiceType);

        return $this;
    }

    public function getInvoiceNo(): string
    {
        return $this->invoiceNo;
    }

    public function setInvoiceNo(string $invoiceNo): static
    {
        $this->invoiceNo = $invoiceNo;

        return $this;
    }

    public function getInvoiceData(): InvoiceData
    {
        return $this->invoiceData ??= new InvoiceData();
    }

    public function setInvoiceData(InvoiceData|array $invoiceData): static
    {
        $this->invoiceData = InvoiceData::wrap($invoiceData);

        return $this;
    }

    public function getStateId(): int
    {
        return $this->stateId;
    }

    public function setStateId(int $stateId): static
    {
        $this->stateId = $stateId;

        return $this;
    }

    public function getPaymentId(): string
    {
        return $this->paymentId;
    }

    public function setPaymentId(string|int $paymentId): static
    {
        $this->paymentId = (string) $paymentId;

        return $this;
    }

    public function getPaymentNo(): string
    {
        return $this->paymentNo;
    }

    public function setPaymentNo(string $paymentNo): static
    {
        $this->paymentNo = $paymentNo;

        return $this;
    }

    public function getPaymentData(): PaymentData
    {
        return $this->paymentData ??= new PaymentData();
    }

    public function setPaymentData(PaymentData|array $paymentData): static
    {
        $this->paymentData = PaymentData::wrap($paymentData);

        return $this;
    }

    public function &getPaymentArgs(): array
    {
        return $this->paymentArgs;
    }

    public function setPaymentArgs(array $paymentArgs): static
    {
        $this->paymentArgs = $paymentArgs;

        return $this;
    }

    public function getPaymentInfo(): PaymentInfo
    {
        return $this->paymentInfo ??= new PaymentInfo();
    }

    public function setPaymentInfo(PaymentInfo|array $paymentInfo): static
    {
        $this->paymentInfo = PaymentInfo::wrap($paymentInfo);

        return $this;
    }

    public function getShippingId(): string
    {
        return $this->shippingId;
    }

    public function setShippingId(string|int $shippingId): static
    {
        $this->shippingId = (string) $shippingId;

        return $this;
    }

    public function getShippingNo(): string
    {
        return $this->shippingNo;
    }

    public function setShippingNo(string $shippingNo): static
    {
        $this->shippingNo = $shippingNo;

        return $this;
    }

    public function getShippingStatus(): string
    {
        return $this->shippingStatus;
    }

    public function setShippingStatus(string $shippingStatus): static
    {
        $this->shippingStatus = $shippingStatus;

        return $this;
    }

    public function getShippingData(): ShippingData
    {
        return $this->shippingData ??= new ShippingData();
    }

    public function setShippingData(ShippingData|array $shippingData): static
    {
        $this->shippingData = ShippingData::wrap($shippingData);

        return $this;
    }

    public function &getShippingArgs(): array
    {
        return $this->shippingArgs;
    }

    public function setShippingArgs(array $shippingArgs): static
    {
        $this->shippingArgs = $shippingArgs;

        return $this;
    }

    public function getShippingInfo(): ShippingInfo
    {
        return $this->shippingInfo ??= new ShippingInfo();
    }

    public function setShippingInfo(ShippingInfo $shippingInfo): static
    {
        $this->shippingInfo = ShippingInfo::wrap($shippingInfo);

        return $this;
    }

    public function getShippingHistory(): ShippingHistoryCollection
    {
        return $this->shippingHistory ??= new ShippingHistoryCollection();
    }

    public function setShippingHistory(ShippingHistoryCollection|array $shippingHistory): static
    {
        $this->shippingHistory = ShippingHistoryCollection::wrap($shippingHistory);

        return $this;
    }

    public function getNote(): string
    {
        return $this->note;
    }

    public function setNote(string $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getPaidAt(): ?Chronos
    {
        return $this->paidAt;
    }

    public function setPaidAt(\DateTimeInterface|string|null $paidAt): static
    {
        $this->paidAt = Chronos::wrapOrNull($paidAt);

        return $this;
    }

    public function getShippedAt(): ?Chronos
    {
        return $this->shippedAt;
    }

    public function setShippedAt(\DateTimeInterface|string|null $shippedAt): static
    {
        $this->shippedAt = Chronos::wrapOrNull($shippedAt);

        return $this;
    }

    public function getReturnedAt(): ?Chronos
    {
        return $this->returnedAt;
    }

    public function setReturnedAt(\DateTimeInterface|string|null $returnedAt): static
    {
        $this->returnedAt = Chronos::wrapOrNull($returnedAt);

        return $this;
    }

    public function getDoneAt(): ?Chronos
    {
        return $this->doneAt;
    }

    public function setDoneAt(\DateTimeInterface|string|null $doneAt): static
    {
        $this->doneAt = Chronos::wrapOrNull($doneAt);

        return $this;
    }

    public function getCancelledAt(): ?Chronos
    {
        return $this->cancelledAt;
    }

    public function setCancelledAt(\DateTimeInterface|string|null $cancelledAt): static
    {
        $this->cancelledAt = Chronos::wrapOrNull($cancelledAt);

        return $this;
    }

    public function getRollbackAt(): ?Chronos
    {
        return $this->rollbackAt;
    }

    public function setRollbackAt(\DateTimeInterface|string|null $rollbackAt): static
    {
        $this->rollbackAt = Chronos::wrapOrNull($rollbackAt);

        return $this;
    }

    public function getExpiryOn(): ?Chronos
    {
        return $this->expiryOn;
    }

    public function setExpiryOn(\DateTimeInterface|string|null $expiryOn): static
    {
        $this->expiryOn = Chronos::wrapOrNull($expiryOn);

        return $this;
    }

    public function getCreated(): ?Chronos
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface|string|null $created): static
    {
        $this->created = Chronos::wrapOrNull($created);

        return $this;
    }

    public function getModified(): ?Chronos
    {
        return $this->modified;
    }

    public function setModified(\DateTimeInterface|string|null $modified): static
    {
        $this->modified = Chronos::wrapOrNull($modified);

        return $this;
    }

    public function getCreatedBy(): int
    {
        return $this->createdBy;
    }

    public function setCreatedBy(int $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getModifiedBy(): int
    {
        return $this->modifiedBy;
    }

    public function setModifiedBy(int $modifiedBy): static
    {
        $this->modifiedBy = $modifiedBy;

        return $this;
    }

    public function &getParams(): array
    {
        return $this->params;
    }

    public function setParams(array $params): static
    {
        $this->params = $params;

        return $this;
    }

    /**
     * @return string
     */
    public function getStateText(): string
    {
        return $this->stateText;
    }

    /**
     * @param  string  $stateText
     *
     * @return  static  Return self to support chaining.
     */
    public function setStateText(string $stateText): static
    {
        $this->stateText = $stateText;

        return $this;
    }

    /**
     * @return string
     */
    public function getSearchIndex(): string
    {
        return $this->searchIndex;
    }

    /**
     * @param  string  $searchIndex
     *
     * @return  static  Return self to support chaining.
     */
    public function setSearchIndex(string $searchIndex): static
    {
        $this->searchIndex = $searchIndex;

        return $this;
    }
}

<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Entity;

use Windwalker\ORM\Attributes\AutoIncrement;
use Windwalker\Core\DateTime\Chronos;
use Windwalker\ORM\Attributes\CastNullable;
use Windwalker\ORM\Attributes\CreatedTime;
use Lyrasoft\Luna\Attributes\Author;
use Windwalker\ORM\Attributes\Cast;
use Windwalker\ORM\Cast\JsonCast;
use Windwalker\ORM\Attributes\CurrentTime;
use Lyrasoft\Luna\Attributes\Modifier;
use Windwalker\ORM\Attributes\Column;
use Windwalker\ORM\Attributes\EntitySetup;
use Windwalker\ORM\Attributes\PK;
use Windwalker\ORM\Attributes\Table;
use Windwalker\ORM\EntityInterface;
use Windwalker\ORM\EntityTrait;
use Windwalker\ORM\Metadata\EntityMetadata;

/**
 * The Order class.
 */
#[Table('orders', 'order')]
class Order implements EntityInterface
{
    use EntityTrait;

    #[Column('cancelled_at')]
    #[CastNullable(Chronos::class)]
    protected ?Chronos $cancelledAt = null;

    #[Column('created')]
    #[CastNullable(Chronos::class)]
    #[CreatedTime]
    protected ?Chronos $created = null;

    #[Column('created_by')]
    #[Author]
    protected int $createdBy = 0;

    #[Column('done_at')]
    #[CastNullable(Chronos::class)]
    protected ?Chronos $doneAt = null;

    #[Column('expiry_on')]
    #[CastNullable(Chronos::class)]
    protected ?Chronos $expiryOn = null;

    #[Column('id'), PK, AutoIncrement]
    protected ?int $id = null;

    #[Column('invoice_data')]
    #[Cast(JsonCast::class)]
    protected array $invoiceData = [];

    #[Column('invoice_no')]
    protected string $invoiceNo = '';

    #[Column('invoice_type')]
    protected string $invoiceType = '';

    #[Column('modified')]
    #[CastNullable(Chronos::class)]
    #[CurrentTime]
    protected ?Chronos $modified = null;

    #[Column('modified_by')]
    #[Modifier]
    protected int $modifiedBy = 0;

    #[Column('no')]
    protected string $no = '';

    #[Column('note')]
    protected string $note = '';

    #[Column('paid_at')]
    #[CastNullable(Chronos::class)]
    protected ?Chronos $paidAt = null;

    #[Column('params')]
    #[Cast(JsonCast::class)]
    protected array $params = [];

    #[Column('payment')]
    protected string $payment = '';

    #[Column('payment_args')]
    #[Cast(JsonCast::class)]
    protected array $paymentArgs = [];

    #[Column('payment_data')]
    #[Cast(JsonCast::class)]
    protected array $paymentData = [];

    #[Column('payment_info')]
    #[Cast(JsonCast::class)]
    protected array $paymentInfo = [];

    #[Column('payment_no')]
    protected string $paymentNo = '';

    #[Column('returned_at')]
    #[CastNullable(Chronos::class)]
    protected ?Chronos $returnedAt = null;

    #[Column('rewards')]
    protected float $rewards = 0.0;

    #[Column('rollback_at')]
    #[CastNullable(Chronos::class)]
    protected ?Chronos $rollbackAt = null;

    #[Column('shipped_at')]
    #[CastNullable(Chronos::class)]
    protected ?Chronos $shippedAt = null;

    #[Column('shipping')]
    protected string $shipping = '';

    #[Column('shipping_args')]
    #[Cast(JsonCast::class)]
    protected array $shippingArgs = [];

    #[Column('shipping_data')]
    #[Cast(JsonCast::class)]
    protected array $shippingData = [];

    #[Column('shipping_history')]
    #[Cast(JsonCast::class)]
    protected array $shippingHistory = [];

    #[Column('shipping_info')]
    #[Cast(JsonCast::class)]
    protected array $shippingInfo = [];

    #[Column('shipping_no')]
    protected string $shippingNo = '';

    #[Column('shipping_status')]
    protected string $shippingStatus = '';

    #[Column('state')]
    protected string $state = '';

    #[Column('total')]
    protected float $total = 0.0;

    #[Column('user_id')]
    protected int $userId = 0;

    #[EntitySetup]
    public static function setup(EntityMetadata $metadata): void
    {
        //
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

    public function getCreated(): ?Chronos
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface|string|null $created): static
    {
        $this->created = Chronos::wrapOrNull($created);

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

    public function getDoneAt(): ?Chronos
    {
        return $this->doneAt;
    }

    public function setDoneAt(\DateTimeInterface|string|null $doneAt): static
    {
        $this->doneAt = Chronos::wrapOrNull($doneAt);

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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getInvoiceData(): array
    {
        return $this->invoiceData;
    }

    public function setInvoiceData(array $invoiceData): static
    {
        $this->invoiceData = $invoiceData;

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

    public function getInvoiceType(): string
    {
        return $this->invoiceType;
    }

    public function setInvoiceType(string $invoiceType): static
    {
        $this->invoiceType = $invoiceType;

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

    public function getModifiedBy(): int
    {
        return $this->modifiedBy;
    }

    public function setModifiedBy(int $modifiedBy): static
    {
        $this->modifiedBy = $modifiedBy;

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

    public function getParams(): array
    {
        return $this->params;
    }

    public function setParams(array $params): static
    {
        $this->params = $params;

        return $this;
    }

    public function getPayment(): string
    {
        return $this->payment;
    }

    public function setPayment(string $payment): static
    {
        $this->payment = $payment;

        return $this;
    }

    public function getPaymentArgs(): array
    {
        return $this->paymentArgs;
    }

    public function setPaymentArgs(array $paymentArgs): static
    {
        $this->paymentArgs = $paymentArgs;

        return $this;
    }

    public function getPaymentData(): array
    {
        return $this->paymentData;
    }

    public function setPaymentData(array $paymentData): static
    {
        $this->paymentData = $paymentData;

        return $this;
    }

    public function getPaymentInfo(): array
    {
        return $this->paymentInfo;
    }

    public function setPaymentInfo(array $paymentInfo): static
    {
        $this->paymentInfo = $paymentInfo;

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

    public function getReturnedAt(): ?Chronos
    {
        return $this->returnedAt;
    }

    public function setReturnedAt(\DateTimeInterface|string|null $returnedAt): static
    {
        $this->returnedAt = Chronos::wrapOrNull($returnedAt);

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

    public function getRollbackAt(): ?Chronos
    {
        return $this->rollbackAt;
    }

    public function setRollbackAt(\DateTimeInterface|string|null $rollbackAt): static
    {
        $this->rollbackAt = Chronos::wrapOrNull($rollbackAt);

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

    public function getShipping(): string
    {
        return $this->shipping;
    }

    public function setShipping(string $shipping): static
    {
        $this->shipping = $shipping;

        return $this;
    }

    public function getShippingArgs(): array
    {
        return $this->shippingArgs;
    }

    public function setShippingArgs(array $shippingArgs): static
    {
        $this->shippingArgs = $shippingArgs;

        return $this;
    }

    public function getShippingData(): array
    {
        return $this->shippingData;
    }

    public function setShippingData(array $shippingData): static
    {
        $this->shippingData = $shippingData;

        return $this;
    }

    public function getShippingHistory(): array
    {
        return $this->shippingHistory;
    }

    public function setShippingHistory(array $shippingHistory): static
    {
        $this->shippingHistory = $shippingHistory;

        return $this;
    }

    public function getShippingInfo(): array
    {
        return $this->shippingInfo;
    }

    public function setShippingInfo(array $shippingInfo): static
    {
        $this->shippingInfo = $shippingInfo;

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

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): static
    {
        $this->state = $state;

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

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): static
    {
        $this->userId = $userId;

        return $this;
    }
}

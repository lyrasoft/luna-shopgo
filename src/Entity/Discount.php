<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Entity;

use App\Enum\DiscountApplyTo;
use App\Enum\DiscountCombine;
use App\Enum\DiscountMethod;
use App\Enum\DiscountType;
use Lyrasoft\Luna\Attributes\Author;
use Lyrasoft\Luna\Attributes\Modifier;
use Unicorn\Enum\BasicState;
use Windwalker\Core\DateTime\Chronos;
use Windwalker\ORM\Attributes\AutoIncrement;
use Windwalker\ORM\Attributes\Cast;
use Windwalker\ORM\Attributes\CastNullable;
use Windwalker\ORM\Attributes\Column;
use Windwalker\ORM\Attributes\CreatedTime;
use Windwalker\ORM\Attributes\CurrentTime;
use Windwalker\ORM\Attributes\PK;
use Windwalker\ORM\Attributes\Table;
use Windwalker\ORM\Cast\JsonCast;
use Windwalker\ORM\EntityInterface;
use Windwalker\ORM\EntityTrait;

/**
 * The Discount class.
 */
#[Table('discounts', 'discount')]
class Discount implements EntityInterface
{
    use EntityTrait;

    #[Column('id'), PK, AutoIncrement]
    protected ?int $id = null;

    #[Column('product_id')]
    protected int $productId = 0;

    #[Column('type')]
    #[Cast(DiscountType::class)]
    protected DiscountType $type;

    #[Column('subtype')]
    protected string $subtype = '';

    #[Column('title')]
    protected string $title = '';

    #[Column('price')]
    protected float $price = 0.0;

    #[Column('publish_up')]
    #[CastNullable(Chronos::class)]
    protected ?Chronos $publishUp = null;

    #[Column('publish_down')]
    #[CastNullable(Chronos::class)]
    protected ?Chronos $publishDown = null;

    #[Column('description')]
    protected string $description = '';

    #[Column('code')]
    protected string $code = '';

    #[Column('notice')]
    protected string $notice = '';

    #[Column('ordering')]
    protected int $ordering = 0;

    #[Column('state')]
    #[Cast('int')]
    #[Cast(BasicState::class)]
    protected BasicState $state;

    #[Column('hide')]
    #[Cast('bool', 'int')]
    protected bool $hide = false;

    #[Column('min_price')]
    protected ?float $minPrice = null;

    #[Column('quantity')]
    protected ?int $quantity = null;

    #[Column('times_per_user')]
    protected ?int $timesPerUser = null;

    #[Column('first_buy')]
    protected ?int $firstBuy = null;

    #[Column('after_registered')]
    protected ?int $afterRegistered = null;

    #[Column('can_rollback')]
    #[Cast('bool', 'int')]
    protected bool $canRollback = false;

    #[Column('combine')]
    #[Cast(DiscountCombine::class)]
    protected DiscountCombine $combine;

    #[Column('combine_targets')]
    #[Cast(JsonCast::class)]
    protected array $combineTargets = [];

    #[Column('categories')]
    #[Cast(JsonCast::class)]
    protected array $categories = [];

    #[Column('prodcuts')]
    #[Cast(JsonCast::class)]
    protected array $prodcuts = [];

    #[Column('payments')]
    #[Cast(JsonCast::class)]
    protected array $payments = [];

    #[Column('shippings')]
    #[Cast(JsonCast::class)]
    protected array $shippings = [];

    #[Column('apply_products')]
    #[Cast(JsonCast::class)]
    protected array $applyProducts = [];

    #[Column('min_product_quantity')]
    protected ?int $minProductQuantity = null;

    #[Column('min_cart_items')]
    protected ?int $minCartItems = null;

    #[Column('min_cart_price')]
    protected ?float $minCartPrice = null;

    #[Column('free_shipping')]
    #[Cast('bool', 'int')]
    protected bool $freeShipping = false;

    #[Column('method')]
    #[Cast(DiscountMethod::class)]
    protected DiscountMethod $method;

    #[Column('apply_to')]
    #[Cast(DiscountApplyTo::class)]
    protected DiscountApplyTo $applyTo;

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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function setProductId(int $productId): static
    {
        $this->productId = $productId;

        return $this;
    }

    public function getType(): DiscountType
    {
        return $this->type;
    }

    public function setType(string|DiscountType $type): static
    {
        $this->type = DiscountType::wrap($type);

        return $this;
    }

    public function getSubtype(): string
    {
        return $this->subtype;
    }

    public function setSubtype(string $subtype): static
    {
        $this->subtype = $subtype;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getPublishUp(): ?Chronos
    {
        return $this->publishUp;
    }

    public function setPublishUp(\DateTimeInterface|string|null $publishUp): static
    {
        $this->publishUp = Chronos::wrapOrNull($publishUp);

        return $this;
    }

    public function getPublishDown(): ?Chronos
    {
        return $this->publishDown;
    }

    public function setPublishDown(\DateTimeInterface|string|null $publishDown): static
    {
        $this->publishDown = Chronos::wrapOrNull($publishDown);

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getNotice(): string
    {
        return $this->notice;
    }

    public function setNotice(string $notice): static
    {
        $this->notice = $notice;

        return $this;
    }

    public function getOrdering(): int
    {
        return $this->ordering;
    }

    public function setOrdering(int $ordering): static
    {
        $this->ordering = $ordering;

        return $this;
    }

    public function getState(): BasicState
    {
        return $this->state;
    }

    public function setState(int|BasicState $state): static
    {
        $this->state = BasicState::wrap($state);

        return $this;
    }

    public function isHide(): bool
    {
        return $this->hide;
    }

    public function setHide(bool $hide): static
    {
        $this->hide = $hide;

        return $this;
    }

    public function getMinPrice(): ?float
    {
        return $this->minPrice;
    }

    public function setMinPrice(?float $minPrice): static
    {
        $this->minPrice = $minPrice ?: null;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): static
    {
        $this->quantity = $quantity ?: null;

        return $this;
    }

    public function getTimesPerUser(): ?int
    {
        return $this->timesPerUser;
    }

    public function setTimesPerUser(?int $timesPerUser): static
    {
        $this->timesPerUser = $timesPerUser ?: null;

        return $this;
    }

    public function getFirstBuy(): ?int
    {
        return $this->firstBuy;
    }

    public function setFirstBuy(?int $firstBuy): static
    {
        $this->firstBuy = $firstBuy ?: null;

        return $this;
    }

    public function getAfterRegistered(): ?int
    {
        return $this->afterRegistered;
    }

    public function setAfterRegistered(?int $afterRegistered): static
    {
        $this->afterRegistered = $afterRegistered ?: null;

        return $this;
    }

    public function isCanRollback(): bool
    {
        return $this->canRollback;
    }

    public function setCanRollback(bool $canRollback): static
    {
        $this->canRollback = $canRollback;

        return $this;
    }

    public function getCombine(): DiscountCombine
    {
        return $this->combine;
    }

    public function setCombine(string|DiscountCombine $combine): static
    {
        $this->combine = DiscountCombine::wrap($combine);

        return $this;
    }

    public function getCombineTargets(): array
    {
        return $this->combineTargets;
    }

    public function setCombineTargets(array $combineTargets): static
    {
        $this->combineTargets = $combineTargets;

        return $this;
    }

    public function getCategories(): array
    {
        return $this->categories;
    }

    public function setCategories(array $categories): static
    {
        $this->categories = $categories;

        return $this;
    }

    public function getProdcuts(): array
    {
        return $this->prodcuts;
    }

    public function setProdcuts(array $prodcuts): static
    {
        $this->prodcuts = $prodcuts;

        return $this;
    }

    public function getPayments(): array
    {
        return $this->payments;
    }

    public function setPayments(array $payments): static
    {
        $this->payments = $payments;

        return $this;
    }

    public function getShippings(): array
    {
        return $this->shippings;
    }

    public function setShippings(array $shippings): static
    {
        $this->shippings = $shippings;

        return $this;
    }

    public function getApplyProducts(): array
    {
        return $this->applyProducts;
    }

    public function setApplyProducts(array $applyProducts): static
    {
        $this->applyProducts = $applyProducts;

        return $this;
    }

    public function getMinProductQuantity(): ?int
    {
        return $this->minProductQuantity;
    }

    public function setMinProductQuantity(?int $minProductQuantity): static
    {
        $this->minProductQuantity = $minProductQuantity ?: null;

        return $this;
    }

    public function getMinCartItems(): ?int
    {
        return $this->minCartItems;
    }

    public function setMinCartItems(?int $minCartItems): static
    {
        $this->minCartItems = $minCartItems ?: null;

        return $this;
    }

    public function getMinCartPrice(): ?float
    {
        return $this->minCartPrice;
    }

    public function setMinCartPrice(?float $minCartPrice): static
    {
        $this->minCartPrice = $minCartPrice ?: null;

        return $this;
    }

    public function isFreeShipping(): bool
    {
        return $this->freeShipping;
    }

    public function setFreeShipping(bool $freeShipping): static
    {
        $this->freeShipping = $freeShipping;

        return $this;
    }

    public function getMethod(): DiscountMethod
    {
        return $this->method;
    }

    public function setMethod(string|DiscountMethod $method): static
    {
        $this->method = DiscountMethod::wrap($method);

        return $this;
    }

    public function getApplyTo(): DiscountApplyTo
    {
        return $this->applyTo;
    }

    public function setApplyTo(string|DiscountApplyTo $applyTo): static
    {
        $this->applyTo = DiscountApplyTo::wrap($applyTo);

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

    public function getParams(): array
    {
        return $this->params;
    }

    public function setParams(array $params): static
    {
        $this->params = $params;

        return $this;
    }
}

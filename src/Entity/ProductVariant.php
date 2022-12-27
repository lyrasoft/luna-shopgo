<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Entity;

use DateTimeInterface;
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
use Windwalker\ORM\Attributes\EntitySetup;
use Windwalker\ORM\Attributes\PK;
use Windwalker\ORM\Attributes\Table;
use Windwalker\ORM\Cast\JsonCast;
use Windwalker\ORM\EntityInterface;
use Windwalker\ORM\EntityTrait;
use Windwalker\ORM\Metadata\EntityMetadata;

/**
 * The ProductVariant class.
 */
#[Table('product_variants', 'product_variant')]
class ProductVariant implements EntityInterface
{
    use EntityTrait;

    #[Column('id'), PK, AutoIncrement]
    protected ?int $id = null;

    #[Column('product_id')]
    protected int $productId = 0;

    #[Column('title')]
    protected string $title = '';

    #[Column('hash')]
    protected string $hash = '';

    #[Column('primary')]
    #[Cast('bool', 'int')]
    protected bool $primary = false;

    #[Column('sku')]
    protected string $sku = '';

    #[Column('upc')]
    protected string $upc = '';

    #[Column('ean')]
    protected string $ean = '';

    #[Column('jan')]
    protected string $jan = '';

    #[Column('isbn')]
    protected string $isbn = '';

    #[Column('mpn')]
    protected string $mpn = '';

    #[Column('quantity')]
    protected int $quantity = 0;

    #[Column('subtract')]
    #[Cast('bool', 'int')]
    protected bool $subtract = false;

    #[Column('price')]
    protected float $price = 0.0;

    #[Column('dimension')]
    #[Cast(JsonCast::class)]
    protected array $dimension = [];

    #[Column('stock_buyable')]
    #[Cast('bool', 'int')]
    protected bool $stockBuyable = false;

    #[Column('stock_text')]
    protected string $stockText = '';

    #[Column('cover')]
    protected string $cover = '';

    #[Column('images')]
    #[Cast(JsonCast::class)]
    protected array $images = [];

    #[Column('options')]
    #[Cast(JsonCast::class)]
    protected array $options = [];

    #[Column('state')]
    #[Cast('int')]
    #[Cast(BasicState::class)]
    protected BasicState $state;

    #[Column('publish_up')]
    #[CastNullable(Chronos::class)]
    protected ?Chronos $publishUp = null;

    #[Column('publish_down')]
    #[CastNullable(Chronos::class)]
    protected ?Chronos $publishDown = null;

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

    #[EntitySetup]
    public static function setup(EntityMetadata $metadata): void
    {
        //
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

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function setProductId(int $productId): static
    {
        $this->productId = $productId;

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

    public function getHash(): string
    {
        return $this->hash;
    }

    public function setHash(string $hash): static
    {
        $this->hash = $hash;

        return $this;
    }

    public function isPrimary(): bool
    {
        return $this->primary;
    }

    public function setPrimary(bool $primary): static
    {
        $this->primary = $primary;

        return $this;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function setSku(string $sku): static
    {
        $this->sku = $sku;

        return $this;
    }

    public function getUpc(): string
    {
        return $this->upc;
    }

    public function setUpc(string $upc): static
    {
        $this->upc = $upc;

        return $this;
    }

    public function getEan(): string
    {
        return $this->ean;
    }

    public function setEan(string $ean): static
    {
        $this->ean = $ean;

        return $this;
    }

    public function getJan(): string
    {
        return $this->jan;
    }

    public function setJan(string $jan): static
    {
        $this->jan = $jan;

        return $this;
    }

    public function isbn(): string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): static
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getMpn(): string
    {
        return $this->mpn;
    }

    public function setMpn(string $mpn): static
    {
        $this->mpn = $mpn;

        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function isSubtract(): bool
    {
        return $this->subtract;
    }

    public function setSubtract(bool $subtract): static
    {
        $this->subtract = $subtract;

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

    public function getDimension(): array
    {
        return $this->dimension;
    }

    public function setDimension(array $dimension): static
    {
        $this->dimension = $dimension;

        return $this;
    }

    public function isStockBuyable(): bool
    {
        return $this->stockBuyable;
    }

    public function setStockBuyable(bool $stockBuyable): static
    {
        $this->stockBuyable = $stockBuyable;

        return $this;
    }

    public function getStockText(): string
    {
        return $this->stockText;
    }

    public function setStockText(string $stockText): static
    {
        $this->stockText = $stockText;

        return $this;
    }

    public function getCover(): string
    {
        return $this->cover;
    }

    public function setCover(string $cover): static
    {
        $this->cover = $cover;

        return $this;
    }

    public function getImages(): array
    {
        return $this->images;
    }

    public function setImages(array $images): static
    {
        $this->images = $images;

        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(array $options): static
    {
        $this->options = $options;

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

    public function getPublishUp(): ?Chronos
    {
        return $this->publishUp;
    }

    public function setPublishUp(DateTimeInterface|string|null $publishUp): static
    {
        $this->publishUp = Chronos::wrapOrNull($publishUp);

        return $this;
    }

    public function getPublishDown(): ?Chronos
    {
        return $this->publishDown;
    }

    public function setPublishDown(DateTimeInterface|string|null $publishDown): static
    {
        $this->publishDown = Chronos::wrapOrNull($publishDown);

        return $this;
    }

    public function getCreated(): ?Chronos
    {
        return $this->created;
    }

    public function setCreated(DateTimeInterface|string|null $created): static
    {
        $this->created = Chronos::wrapOrNull($created);

        return $this;
    }

    public function getModified(): ?Chronos
    {
        return $this->modified;
    }

    public function setModified(DateTimeInterface|string|null $modified): static
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

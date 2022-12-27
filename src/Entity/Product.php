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
use Lyrasoft\Luna\Attributes\Slugify;
use Windwalker\ORM\Attributes\Cast;
use Windwalker\Core\DateTime\Chronos;
use Windwalker\ORM\Attributes\CastNullable;
use Windwalker\ORM\Attributes\CreatedTime;
use Lyrasoft\Luna\Attributes\Author;
use Windwalker\ORM\Cast\JsonCast;
use Windwalker\ORM\Attributes\CurrentTime;
use Lyrasoft\Luna\Attributes\Modifier;
use Unicorn\Enum\BasicState;
use Windwalker\ORM\Attributes\Column;
use Windwalker\ORM\Attributes\EntitySetup;
use Windwalker\ORM\Attributes\PK;
use Windwalker\ORM\Attributes\Table;
use Windwalker\ORM\EntityInterface;
use Windwalker\ORM\EntityTrait;
use Windwalker\ORM\Metadata\EntityMetadata;

/**
 * The Product class.
 */
#[Table('products', 'product')]
class Product implements EntityInterface
{
    use EntityTrait;

    #[Column('alias')]
    #[Slugify]
    protected string $alias = '';

    #[Column('can_attach')]
    #[Cast('bool', 'int')]
    protected bool $canAttach = false;

    #[Column('category_id')]
    protected int $categoryId = 0;

    #[Column('created')]
    #[CastNullable(Chronos::class)]
    #[CreatedTime]
    protected ?Chronos $created = null;

    #[Column('created_by')]
    #[Author]
    protected int $createdBy = 0;

    #[Column('description')]
    protected string $description = '';

    #[Column('hide')]
    #[Cast('bool', 'int')]
    protected bool $hide = false;

    #[Column('hits')]
    protected int $hits = 0;

    #[Column('id'), PK, AutoIncrement]
    protected ?int $id = null;

    #[Column('intro')]
    protected string $intro = '';

    #[Column('meta')]
    #[Cast(JsonCast::class)]
    protected array $meta = [];

    #[Column('model')]
    protected string $model = '';

    #[Column('modified')]
    #[CastNullable(Chronos::class)]
    #[CurrentTime]
    protected ?Chronos $modified = null;

    #[Column('modified_by')]
    #[Modifier]
    protected int $modifiedBy = 0;

    #[Column('ordering')]
    protected int $ordering = 0;

    #[Column('origin_price')]
    protected float $originPrice = 0.0;

    #[Column('params')]
    #[Cast(JsonCast::class)]
    protected array $params = [];

    #[Column('primary_variant_id')]
    protected int $primaryVariantId = 0;

    #[Column('safe_quantity')]
    protected int $safeQuantity = 0;

    #[Column('search_index')]
    protected string $searchIndex = '';

    #[Column('shippings')]
    #[Cast(JsonCast::class)]
    protected array $shippings = [];

    #[Column('state')]
    #[Cast('int')]
    #[Cast(BasicState::class)]
    protected BasicState $state;

    #[Column('title')]
    protected string $title = '';

    #[Column('variants')]
    protected int $variants = 0;

    #[EntitySetup]
    public static function setup(EntityMetadata $metadata): void
    {
        //
    }

    public function getAlias(): string
    {
        return $this->alias;
    }

    public function setAlias(string $alias): static
    {
        $this->alias = $alias;

        return $this;
    }

    public function isCanAttach(): bool
    {
        return $this->canAttach;
    }

    public function setCanAttach(bool $canAttach): static
    {
        $this->canAttach = $canAttach;

        return $this;
    }

    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    public function setCategoryId(int $categoryId): static
    {
        $this->categoryId = $categoryId;

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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

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

    public function getHits(): int
    {
        return $this->hits;
    }

    public function setHits(int $hits): static
    {
        $this->hits = $hits;

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

    public function getIntro(): string
    {
        return $this->intro;
    }

    public function setIntro(string $intro): static
    {
        $this->intro = $intro;

        return $this;
    }

    public function getMeta(): array
    {
        return $this->meta;
    }

    public function setMeta(array $meta): static
    {
        $this->meta = $meta;

        return $this;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;

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

    public function getOrdering(): int
    {
        return $this->ordering;
    }

    public function setOrdering(int $ordering): static
    {
        $this->ordering = $ordering;

        return $this;
    }

    public function getOriginPrice(): float
    {
        return $this->originPrice;
    }

    public function setOriginPrice(float $originPrice): static
    {
        $this->originPrice = $originPrice;

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

    public function getPrimaryVariantId(): int
    {
        return $this->primaryVariantId;
    }

    public function setPrimaryVariantId(int $primaryVariantId): static
    {
        $this->primaryVariantId = $primaryVariantId;

        return $this;
    }

    public function getSafeQuantity(): int
    {
        return $this->safeQuantity;
    }

    public function setSafeQuantity(int $safeQuantity): static
    {
        $this->safeQuantity = $safeQuantity;

        return $this;
    }

    public function getSearchIndex(): string
    {
        return $this->searchIndex;
    }

    public function setSearchIndex(string $searchIndex): static
    {
        $this->searchIndex = $searchIndex;

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

    public function getState(): BasicState
    {
        return $this->state;
    }

    public function setState(int|BasicState $state): static
    {
        $this->state = BasicState::wrap($state);

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

    public function getVariants(): int
    {
        return $this->variants;
    }

    public function setVariants(int $variants): static
    {
        $this->variants = $variants;

        return $this;
    }
}

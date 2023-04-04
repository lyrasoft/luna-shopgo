<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Entity;

use DateTimeInterface;
use Lyrasoft\Luna\Attributes\Author;
use Lyrasoft\Luna\Attributes\Modifier;
use Lyrasoft\Luna\Attributes\Slugify;
use Unicorn\Enum\BasicState;
use Windwalker\Core\DateTime\Chronos;
use Windwalker\Core\Form\Exception\ValidateFailException;
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
use Windwalker\ORM\Event\BeforeCopyEvent;
use Windwalker\ORM\Event\BeforeSaveEvent;
use Windwalker\ORM\Metadata\EntityMetadata;
use Windwalker\Utilities\Str;

/**
 * The Payment class.
 */
#[Table('payments', 'payment')]
#[\AllowDynamicProperties]
class Payment implements EntityInterface
{
    use EntityTrait;

    #[Column('id'), PK, AutoIncrement]
    protected ?int $id = null;

    #[Column('location_category_id')]
    protected int $locationCategoryId = 0;

    #[Column('location_id')]
    protected int $locationId = 0;

    #[Column('order_state_id')]
    protected int $orderStateId = 0;

    #[Column('classname')]
    protected string $classname = '';

    #[Column('type')]
    protected string $type = '';

    #[Column('title')]
    protected string $title = '';
    #[Column('subtitle')]
    protected string $subtitle = '';

    #[Column('alias')]
    #[Slugify]
    protected string $alias = '';

    #[Column('description')]
    protected string $description = '';

    #[Column('note')]
    protected string $note = '';

    #[Column('image')]
    protected string $image = '';

    #[Column('state')]
    #[Cast('int')]
    #[Cast(BasicState::class)]
    protected BasicState $state;

    #[Column('ordering')]
    protected int $ordering = 0;

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

    #[BeforeSaveEvent]
    public static function beforeSave(BeforeSaveEvent $event): void
    {
        $data = $event->getData();
        $orm = $event->getORM();

        $exists = $orm->from(static::class)
            ->where('alias', $data['alias'])
            ->where('id', '!=', $data['id'] ?? 0)
            ->get();

        if ($exists) {
            throw new ValidateFailException('Duplicated alias');
        }
    }

    #[BeforeCopyEvent]
    public static function beforeCopy(BeforeCopyEvent $event): void
    {
        $orm = $event->getORM();
        $data = &$event->getData();

        do {
            $data['title'] = Str::increment($data['title']);

            $exists = $orm->findOne(static::class, ['title' => $data['title']]);
        } while ($exists !== null);

        do {
            $data['alias'] = Str::increment($data['alias'], '%s-%d');

            $exists = $orm->findOne(static::class, ['alias' => $data['alias']]);
        } while ($exists !== null);
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

    public function getLocationCategoryId(): int
    {
        return $this->locationCategoryId;
    }

    public function setLocationCategoryId(int $locationCategoryId): static
    {
        $this->locationCategoryId = $locationCategoryId;

        return $this;
    }

    public function getLocationId(): int
    {
        return $this->locationId;
    }

    public function setLocationId(int $locationId): static
    {
        $this->locationId = $locationId;

        return $this;
    }

    public function getOrderStateId(): int
    {
        return $this->orderStateId;
    }

    public function setOrderStateId(int $orderStateId): static
    {
        $this->orderStateId = $orderStateId;

        return $this;
    }

    public function getClassname(): string
    {
        return $this->classname;
    }

    public function setClassname(string $classname): static
    {
        $this->classname = $classname;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

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

    public function getOrdering(): int
    {
        return $this->ordering;
    }

    public function setOrdering(int $ordering): static
    {
        $this->ordering = $ordering;

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

    public function &getParams(): array
    {
        return $this->params;
    }

    public function setParams(array $params): static
    {
        $this->params = $params;

        return $this;
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

    public function getNote(): string
    {
        return $this->note;
    }

    public function setNote(string $note): static
    {
        $this->note = $note;

        return $this;
    }
    public function getSubtitle() : string
    {
        return $this->subtitle;
    }
    public function setSubtitle(string $subtitle) : static
    {
        $this->subtitle = $subtitle;
        return $this;
    }
}

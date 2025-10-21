<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Entity;

use DateTimeInterface;
use Lyrasoft\Luna\Attributes\Author;
use Lyrasoft\Luna\Attributes\Modifier;
use Lyrasoft\Luna\Attributes\Slugify;
use Lyrasoft\Luna\Data\MetaData;
use Lyrasoft\Luna\Entity\User;
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
 * The Manufacturer class.
 */
// phpcs:disable
// todo: remove this when phpcs supports 8.4
#[Table('manufacturers', 'manufacturer')]
#[\AllowDynamicProperties]
class Manufacturer implements EntityInterface
{
    use EntityTrait;

    #[Column('id'), PK, AutoIncrement]
    public ?int $id = null;

    #[Column('title')]
    public string $title = '';

    #[Column('alias')]
    #[Slugify]
    public string $alias = '';

    #[Column('image')]
    public string $image = '';

    #[Column('introtext')]
    public string $introtext = '';

    #[Column('page_id')]
    public int $pageId = 0;

    #[Column('state')]
    #[Cast('int')]
    #[Cast(BasicState::class)]
    public BasicState $state {
        set(BasicState|int $value) => $this->state = BasicState::wrap($value);
    }

    #[Column('ordering')]
    public int $ordering = 0;

    #[Column('meta')]
    #[Cast(MetaData::class, JsonCast::class)]
    public MetaData $meta {
        set(MetaData|array|null $value) => $this->meta = MetaData::wrap($value);
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

    #[Column('language')]
    public string $language = '';

    #[Column('params')]
    #[Cast(JsonCast::class)]
    public array $params = [];

    #[EntitySetup]
    public static function setup(EntityMetadata $metadata): void
    {
        $rm = $metadata->getRelationManager();

        $rm->manyToOne('user')
            ->targetTo(User::class, created_by: 'id');
    }
}

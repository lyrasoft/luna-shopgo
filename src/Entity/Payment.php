<?php

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
// phpcs:disable
// todo: remove this when phpcs supports 8.4
#[Table('payments', 'payment')]
#[\AllowDynamicProperties]
class Payment implements EntityInterface
{
    use EntityTrait;

    #[Column('id'), PK, AutoIncrement]
    public mixed $id = null;

    #[Column('location_category_id')]
    public int $locationCategoryId = 0;

    #[Column('location_id')]
    public int $locationId = 0;

    #[Column('order_state_id')]
    public int $orderStateId = 0;

    #[Column('classname')]
    public string $classname = '';

    #[Column('type')]
    public string $type = '';

    #[Column('title')]
    public string $title = '';
    #[Column('subtitle')]
    public string $subtitle = '';

    #[Column('alias')]
    #[Slugify]
    public string $alias = '';

    #[Column('description')]
    public string $description = '';

    #[Column('note')]
    public string $note = '';

    #[Column('image')]
    public string $image = '';

    #[Column('state')]
    #[Cast('int')]
    #[Cast(BasicState::class)]
    public BasicState $state {
        set(BasicState|int $value) => $this->state = BasicState::wrap($value);
    }

    #[Column('ordering')]
    public int $ordering = 0;

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

    #[EntitySetup]
    public static function setup(EntityMetadata $metadata): void
    {
        //
    }

    #[BeforeSaveEvent]
    public static function beforeSave(BeforeSaveEvent $event): void
    {
        $data = $event->data;
        $orm = $event->orm;

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
        $orm = $event->orm;
        $data = &$event->data;

        do {
            $data['title'] = Str::increment($data['title']);

            $exists = $orm->findOne(static::class, ['title' => $data['title']]);
        } while ($exists !== null);

        do {
            $data['alias'] = Str::increment($data['alias'], '%s-%d');

            $exists = $orm->findOne(static::class, ['alias' => $data['alias']]);
        } while ($exists !== null);
    }
}

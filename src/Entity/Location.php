<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Entity;

use DateTimeInterface;
use Lyrasoft\Luna\Attributes\Author;
use Lyrasoft\Luna\Attributes\Modifier;
use Lyrasoft\ShopGo\Enum\LocationType;
use Unicorn\Enum\BasicState;
use Windwalker\Core\DateTime\Chronos;
use Windwalker\ORM\Attributes\AutoIncrement;
use Windwalker\ORM\Attributes\Cast;
use Windwalker\ORM\Attributes\CastNullable;
use Windwalker\ORM\Attributes\Column;
use Windwalker\ORM\Attributes\CreatedTime;
use Windwalker\ORM\Attributes\CurrentTime;
use Windwalker\ORM\Attributes\EntitySetup;
use Windwalker\ORM\Attributes\NestedSet;
use Windwalker\ORM\Attributes\PK;
use Windwalker\ORM\Cast\JsonCast;
use Windwalker\ORM\Metadata\EntityMetadata;
use Windwalker\ORM\Nested\NestedEntityInterface;
use Windwalker\ORM\Nested\NestedEntityTrait;

/**
 * The Location class.
 */
// phpcs:disable
// todo: remove this when phpcs supports 8.4
#[NestedSet('locations', 'location')]
#[\AllowDynamicProperties]
class Location implements NestedEntityInterface
{
    use NestedEntityTrait;

    #[Column('id'), PK, AutoIncrement]
    public ?int $id = null;

    #[Column('category_id')]
    public int $categoryId = 0;

    #[Column('type')]
    #[Cast(LocationType::class)]
    public LocationType $type {
        set(LocationType|string $value) => $this->type = LocationType::wrap($value);
    }

    #[Column('region')]
    public string $region = '';

    #[Column('subregion')]
    public string $subregion = '';

    #[Column('title')]
    public string $title = '';

    #[Column('code')]
    public string $code = '';

    #[Column('code3')]
    public string $code3 = '';

    #[Column('address_format')]
    public string $addressFormat = '';

    #[Column('postcode_required')]
    #[Cast('bool', 'int')]
    public bool $postcodeRequired = false;

    #[Column('native')]
    public string $native = '';

    #[Column('has_states')]
    #[Cast('bool', 'int')]
    public bool $hasStates = false;

    #[Column('call_prefix')]
    public string $callPrefix = '';

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

    #[Column('can_ship')]
    #[Cast('bool', 'int')]
    public bool $canShip = false;

    #[Column('modified_by')]
    #[Modifier]
    public int $modifiedBy = 0;

    #[Column('state')]
    #[Cast('int')]
    #[Cast(BasicState::class)]
    public BasicState $state {
        set(BasicState|int $value) => $this->state = BasicState::wrap($value);
    }

    #[Column('params')]
    #[Cast(JsonCast::class)]
    public array $params = [];

    #[EntitySetup]
    public static function setup(EntityMetadata $metadata): void
    {
        //
    }

    public function getPrimaryKeyValue(): ?int
    {
        return $this->id;
    }
}

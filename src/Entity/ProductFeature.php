<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Entity;

use DateTimeInterface;
use Lyrasoft\ShopGo\Data\ListOptionCollection;
use Lyrasoft\ShopGo\Enum\ProductFeatureType;
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
 * The ProductFeature class.
 */
// phpcs:disable
// todo: remove this when phpcs supports 8.4
#[Table('product_features', 'product_feature')]
#[\AllowDynamicProperties]
class ProductFeature implements EntityInterface
{
    use EntityTrait;

    #[Column('id'), PK, AutoIncrement]
    public ?int $id = null;

    #[Column('type')]
    #[Cast(ProductFeatureType::class)]
    public ProductFeatureType $type {
        set(ProductFeatureType|string $value) => $this->type = ProductFeatureType::wrap($value);
    }

    #[Column('title')]
    public string $title = '';

    #[Column('default')]
    public string $default = '';

    #[Column('note')]
    public string $note = '';

    #[Column('ordering')]
    public int $ordering = 0;

    #[Column('state')]
    #[Cast('int')]
    #[Cast(BasicState::class)]
    public BasicState $state {
        set(BasicState|int $value) => $this->state = BasicState::wrap($value);
    }

    #[Column('options')]
    #[Cast(JsonCast::class)]
    #[Cast(ListOptionCollection::class)]
    public ListOptionCollection $options {
        set(ListOptionCollection|array|null $value) => $this->options = ListOptionCollection::wrap($value);
    }

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
}

<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Entity;

use DateTimeInterface;
use Lyrasoft\Luna\Attributes\Author;
use Lyrasoft\Luna\Attributes\Modifier;
use Lyrasoft\Luna\Attributes\Slugify;
use Lyrasoft\Luna\Data\MetaData;
use Unicorn\Enum\BasicState;
use Windwalker\Core\DateTime\Chronos;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\RouteUri;
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
 * The Product class.
 */
// phpcs:disable
// todo: remove this when phpcs supports 8.4
#[Table('products', 'product')]
#[\AllowDynamicProperties]
class Product implements EntityInterface
{
    use EntityTrait;

    #[Column('id'), PK, AutoIncrement]
    public ?int $id = null;

    #[Column('category_id')]
    public int $categoryId = 0;

    #[Column('primary_variant_id')]
    public int $primaryVariantId = 0;

    #[Column('model')]
    public string $model = '';

    #[Column('title')]
    public string $title = '';

    #[Column('alias')]
    #[Slugify]
    public string $alias = '';

    #[Column('origin_price')]
    public float $originPrice = 0.0;

    #[Column('safe_stock')]
    public int $safeStock = 0;

    #[Column('intro')]
    public string $intro = '';

    #[Column('description')]
    public string $description = '';

    #[Column('meta')]
    #[Cast(JsonCast::class)]
    #[Cast(MetaData::class)]
    public MetaData $meta {
        set(MetaData|array|null $value) => $this->meta = MetaData::wrap($value);
    }

    #[Column('can_attach')]
    #[Cast('bool', 'int')]
    public bool $canAttach = false;

    #[Column('variants')]
    public int $variants = 0;

    #[Column('ordering')]
    public int $ordering = 0;

    #[Column('hide')]
    #[Cast('bool', 'int')]
    public bool $hide = false;

    #[Column('state')]
    #[Cast('int')]
    #[Cast(BasicState::class)]
    public BasicState $state {
        set(BasicState|int $value) => $this->state = BasicState::wrap($value);
    }

    #[Column('search_index')]
    public string $searchIndex = '';

    #[Column('shippings')]
    #[Cast(JsonCast::class)]
    public array $shippings = [];

    #[Column('publish_up')]
    #[CastNullable(Chronos::class)]
    public ?Chronos $publishUp = null {
        set(\DateTimeInterface|string|null $value) => $this->publishUp = Chronos::tryWrap($value);
    }

    #[Column('publish_down')]
    #[CastNullable(Chronos::class)]
    public ?Chronos $publishDown = null {
        set(\DateTimeInterface|string|null $value) => $this->publishDown = Chronos::tryWrap($value);
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

    #[Column('hits')]
    public int $hits = 0;

    #[Column('params')]
    #[Cast(JsonCast::class)]
    public array $params = [];

    #[EntitySetup]
    public static function setup(EntityMetadata $metadata): void
    {
        //
    }

    public function makeLink(Navigator $nav): RouteUri
    {
        return $nav->to('front::product_item')->id($this->id)->alias($this->alias);
    }
}

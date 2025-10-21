<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Entity;

use DateTimeInterface;
use Lyrasoft\ShopGo\Data\ListOptionCollection;
use Lyrasoft\ShopGo\Data\ProductDimension;
use Lyrasoft\ShopGo\Entity\Traits\ProductVariantTrait;
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
// phpcs:disable
// todo: remove this when phpcs supports 8.4
#[Table('product_variants', 'product_variant')]
#[\AllowDynamicProperties]
class ProductVariant implements EntityInterface
{
    use EntityTrait;
    use ProductVariantTrait;

    #[Column('id'), PK, AutoIncrement]
    public ?int $id = null;

    #[Column('product_id')]
    public int $productId = 0;

    #[Column('title')]
    public string $title = '';

    #[Column('hash')]
    public string $hash = '';

    #[Column('primary')]
    #[Cast('bool', 'int')]
    public bool $primary = false;

    #[Column('sku')]
    public string $sku = '';

    #[Column('upc')]
    public string $upc = '';

    #[Column('ean')]
    public string $ean = '';

    #[Column('jan')]
    public string $jan = '';

    #[Column('isbn')]
    public string $isbn = '';

    #[Column('mpn')]
    public string $mpn = '';

    #[Column('stock_quantity')]
    public int $stockQuantity = 0;

    #[Column('subtract')]
    #[Cast('bool', 'int')]
    public bool $subtract = false;

    #[Column('price')]
    public float $price = 0.0;

    #[Column('dimension')]
    #[Cast(JsonCast::class)]
    #[Cast(ProductDimension::class)]
    public ProductDimension $dimension {
        set(ProductDimension|array|null $value) => $this->dimension = ProductDimension::wrap($value);
        get => $this->dimension ??= new ProductDimension();
    }

    #[Column('out_of_stock_text')]
    public string $outOfStockText = '';

    #[Column('cover')]
    public string $cover = '';

    #[Column('images')]
    #[Cast(JsonCast::class)]
    public array $images = [];

    #[Column('options')]
    #[Cast(JsonCast::class)]
    #[Cast(ListOptionCollection::class)]
    public ListOptionCollection $options {
        set(ListOptionCollection|array|null $value) => $this->options = ListOptionCollection::wrap($value);
        get => $this->options ??= new ListOptionCollection();
    }

    #[Column('state')]
    #[Cast('int')]
    #[Cast(BasicState::class)]
    public BasicState $state {
        set(BasicState|int $value) => $this->state = BasicState::wrap($value);
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

    public function getSearchIndex(): string
    {
        $indexes = [
            $this->hash,
            $this->sku,
            $this->upc,
            $this->ean,
            $this->jan,
            $this->isbn,
            $this->mpn,
        ];

        return implode('|', array_filter($indexes));
    }
}

<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Entity;

use Lyrasoft\ShopGo\Cart\Price\PriceSet;
use Lyrasoft\ShopGo\Data\ListOptionCollection;
use Windwalker\ORM\Attributes\AutoIncrement;
use Windwalker\ORM\Attributes\Cast;
use Windwalker\ORM\Attributes\Column;
use Windwalker\ORM\Attributes\OnDelete;
use Windwalker\ORM\Attributes\OneToMany;
use Windwalker\ORM\Attributes\OnUpdate;
use Windwalker\ORM\Attributes\PK;
use Windwalker\ORM\Attributes\Table;
use Windwalker\ORM\Attributes\TargetTo;
use Windwalker\ORM\Cast\JsonCast;
use Windwalker\ORM\EntityInterface;
use Windwalker\ORM\EntityTrait;
use Windwalker\ORM\Relation\Action;
use Windwalker\ORM\Relation\RelationCollection;

/**
 * The OrderItem class.
 */
// phpcs:disable
// todo: remove this when phpcs supports 8.4
#[Table('order_items', 'order_item')]
#[\AllowDynamicProperties]
class OrderItem implements EntityInterface
{
    use EntityTrait;

    #[Column('id'), PK, AutoIncrement]
    public ?int $id = null;

    #[Column('parent_id')]
    public int $parentId = 0;

    #[Column('order_id')]
    public int $orderId = 0;

    #[Column('product_id')]
    public int $productId = 0;

    #[Column('variant_id')]
    public int $variantId = 0;

    #[Column('attachment_id')]
    public int $attachmentId = 0;

    #[Column('variant_hash')]
    public string $variantHash = '';

    #[Column('key')]
    public string $key = '';

    #[Column('title')]
    public string $title = '';

    #[Column('variant_title')]
    public string $variantTitle = '';

    #[Column('image')]
    public string $image = '';

    #[Column('product_data')]
    #[Cast(JsonCast::class)]
    public array $productData = [];

    #[Column('quantity')]
    public int $quantity = 0;

    #[Column('price_unit')]
    public float $priceUnit = 0.0;

    #[Column('base_price_unit')]
    public float $basePriceUnit = 0.0;

    #[Column('total')]
    public float $total = 0.0;

    #[Column('price_set')]
    #[Cast(JsonCast::class)]
    #[Cast(PriceSet::class)]
    public PriceSet $priceSet;

    #[Column('options')]
    #[Cast(JsonCast::class)]
    #[Cast(ListOptionCollection::class)]
    public ListOptionCollection $options {
        set(ListOptionCollection|array|null $value) => $this->options = ListOptionCollection::wrap($value);
        get => $this->options ??= new ListOptionCollection();
    }

    #[Column('params')]
    #[Cast(JsonCast::class)]
    public array $params = [];

    #[
        OneToMany,
        TargetTo(OrderItem::class, id: 'parent_id', order_id: 'order_id'),
        OnUpdate(Action::IGNORE),
        OnDelete(Action::CASCADE)
    ]
    public RelationCollection|null $attachments = null;

    public function getFullTitle(string $delimiter = ' | '): string
    {
        if ($this->variantHash) {
            return $this->title . $delimiter . $this->variantTitle;
        }

        return $this->title;
    }

    /**
     * @return RelationCollection
     */
    public function getAttachments(): RelationCollection
    {
        return $this->fetchCollection('attachments');
    }

    /**
     * @param  RelationCollection  $attachments
     *
     * @return  static  Return self to support chaining.
     */
    public function setAttachments(RelationCollection $attachments): static
    {
        $this->attachments = $attachments;

        return $this;
    }
}

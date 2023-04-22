<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    MIT
 */

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
#[Table('order_items', 'order_item')]
#[\AllowDynamicProperties]
class OrderItem implements EntityInterface
{
    use EntityTrait;

    #[Column('id'), PK, AutoIncrement]
    protected ?int $id = null;

    #[Column('parent_id')]
    protected int $parentId = 0;

    #[Column('order_id')]
    protected int $orderId = 0;

    #[Column('product_id')]
    protected int $productId = 0;

    #[Column('variant_id')]
    protected int $variantId = 0;

    #[Column('attachment_id')]
    protected int $attachmentId = 0;

    #[Column('variant_hash')]
    protected string $variantHash = '';

    #[Column('key')]
    protected string $key = '';

    #[Column('title')]
    protected string $title = '';

    #[Column('variant_title')]
    protected string $variantTitle = '';

    #[Column('image')]
    protected string $image = '';

    #[Column('product_data')]
    #[Cast(JsonCast::class)]
    protected array $productData = [];

    #[Column('quantity')]
    protected int $quantity = 0;

    #[Column('price_unit')]
    protected float $priceUnit = 0.0;

    #[Column('base_price_unit')]
    protected float $basePriceUnit = 0.0;

    #[Column('total')]
    protected float $total = 0.0;

    #[Column('price_set')]
    #[Cast(JsonCast::class)]
    #[Cast(PriceSet::class)]
    protected PriceSet $priceSet;

    #[Column('options')]
    #[Cast(JsonCast::class)]
    #[Cast(ListOptionCollection::class)]
    protected ListOptionCollection $options;

    #[Column('params')]
    #[Cast(JsonCast::class)]
    protected array $params = [];

    #[
        OneToMany,
        TargetTo(OrderItem::class, id: 'parent_id', order_id: 'order_id'),
        OnUpdate(Action::IGNORE),
        OnDelete(Action::CASCADE)
    ]
    protected RelationCollection|null $attachments = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getParentId(): int
    {
        return $this->parentId;
    }

    public function setParentId(int $parentId): static
    {
        $this->parentId = $parentId;

        return $this;
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function setOrderId(int $orderId): static
    {
        $this->orderId = $orderId;

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

    public function getVariantId(): int
    {
        return $this->variantId;
    }

    public function setVariantId(int $variantId): static
    {
        $this->variantId = $variantId;

        return $this;
    }

    public function isAttachmentId(): int
    {
        return $this->attachmentId;
    }

    public function setAttachmentId(int $attachmentId): static
    {
        $this->attachmentId = $attachmentId;

        return $this;
    }

    public function getVariantHash(): string
    {
        return $this->variantHash;
    }

    public function setVariantHash(string $variantHash): static
    {
        $this->variantHash = $variantHash;

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

    public function getVariantTitle(): string
    {
        return $this->variantTitle;
    }

    public function setVariantTitle(string $variantTitle): static
    {
        $this->variantTitle = $variantTitle;

        return $this;
    }

    public function getFullTitle(string $delimiter = ' | '): string
    {
        if ($this->getVariantHash()) {
            return $this->getTitle() . $delimiter . $this->getVariantTitle();
        }

        return $this->getTitle();
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

    public function getProductData(): array
    {
        return $this->productData;
    }

    public function setProductData(array $productData): static
    {
        $this->productData = $productData;

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

    public function getPriceUnit(): float
    {
        return $this->priceUnit;
    }

    public function setPriceUnit(float $priceUnit): static
    {
        $this->priceUnit = $priceUnit;

        return $this;
    }

    public function getBasePriceUnit(): float
    {
        return $this->basePriceUnit;
    }

    public function setBasePriceUnit(float $basePriceUnit): static
    {
        $this->basePriceUnit = $basePriceUnit;

        return $this;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function setTotal(float $total): static
    {
        $this->total = $total;

        return $this;
    }

    public function getPriceSet(): PriceSet
    {
        return $this->priceSet ??= new PriceSet();
    }

    public function setPriceSet(PriceSet|array $priceSet): static
    {
        $this->priceSet = PriceSet::wrap($priceSet);

        return $this;
    }

    public function getOptions(): ListOptionCollection
    {
        return $this->options ?? new ListOptionCollection();
    }

    public function setOptions(ListOptionCollection|array $options): static
    {
        $this->options = ListOptionCollection::wrap($options);

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

    public function getAttachmentId(): int
    {
        return $this->attachmentId;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): static
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @return RelationCollection
     */
    public function getAttachments(): RelationCollection
    {
        return $this->loadCollection('attachments');
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

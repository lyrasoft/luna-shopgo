<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Entity;

use Lyrasoft\Luna\Attributes\Author;
use Lyrasoft\Luna\Attributes\Modifier;
use Lyrasoft\ShopGo\Enum\DiscountApplyTo;
use Lyrasoft\ShopGo\Enum\DiscountCombine;
use Lyrasoft\ShopGo\Enum\DiscountMethod;
use Lyrasoft\ShopGo\Enum\DiscountType;
use Unicorn\Enum\BasicState;
use Windwalker\Core\DateTime\Chronos;
use Windwalker\ORM\Attributes\AutoIncrement;
use Windwalker\ORM\Attributes\Cast;
use Windwalker\ORM\Attributes\CastNullable;
use Windwalker\ORM\Attributes\Column;
use Windwalker\ORM\Attributes\CreatedTime;
use Windwalker\ORM\Attributes\CurrentTime;
use Windwalker\ORM\Attributes\PK;
use Windwalker\ORM\Attributes\Table;
use Windwalker\ORM\Cast\JsonCast;
use Windwalker\ORM\EntityInterface;
use Windwalker\ORM\EntityTrait;

/**
 * The Discount class.
 */
// phpcs:disable
// todo: remove this when phpcs supports 8.4
#[Table('discounts', 'discount')]
#[\AllowDynamicProperties]
class Discount implements EntityInterface
{
    use EntityTrait;

    #[Column('id'), PK, AutoIncrement]
    public ?int $id = null;

    #[Column('product_id')]
    public int $productId = 0;

    #[Column('type')]
    #[Cast(DiscountType::class)]
    public DiscountType $type {
        set(DiscountType|string $value) => $this->type = DiscountType::wrap($value);
    }

    #[Column('subtype')]
    public string $subtype = '';

    #[Column('title')]
    public string $title = '';

    #[Column('price')]
    public float $price = 0.0;

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

    #[Column('description')]
    public string $description = '';

    #[Column('code')]
    public string $code = '';

    #[Column('notice')]
    public string $notice = '';

    #[Column('ordering')]
    public int $ordering = 0;

    #[Column('state')]
    #[Cast('int')]
    #[Cast(BasicState::class)]
    public BasicState $state {
        set(BasicState|int $value) => $this->state = BasicState::wrap($value);
    }

    #[Column('hide')]
    #[Cast('bool', 'int')]
    public bool $hide = false;

    #[Column('min_price')]
    public ?float $minPrice = null;

    #[Column('quantity')]
    public ?int $quantity = null;

    #[Column('times_per_user')]
    public ?int $timesPerUser = null;

    #[Column('first_buy')]
    public ?int $firstBuy = null;

    #[Column('after_registered')]
    public ?int $afterRegistered = null;

    #[Column('can_rollback')]
    #[Cast('bool', 'int')]
    public bool $canRollback = false;

    #[Column('combine')]
    #[Cast(DiscountCombine::class)]
    public DiscountCombine $combine {
        set(DiscountCombine|string $value) => $this->combine = DiscountCombine::wrap($value);
    }

    #[Column('combine_targets')]
    #[Cast(JsonCast::class)]
    public array $combineTargets = [];

    #[Column('users')]
    #[Cast(JsonCast::class)]
    public array $users = [];

    #[Column('categories')]
    #[Cast(JsonCast::class)]
    public array $categories = [];

    #[Column('products')]
    #[Cast(JsonCast::class)]
    public array $products = [];

    #[Column('tags')]
    #[Cast(JsonCast::class)]
    public array $tags = [];

    #[Column('payments')]
    #[Cast(JsonCast::class)]
    public array $payments = [];

    #[Column('shippings')]
    #[Cast(JsonCast::class)]
    public array $shippings = [];

    #[Column('apply_products')]
    #[Cast(JsonCast::class)]
    public array $applyProducts = [];

    #[Column('min_product_quantity')]
    public ?int $minProductQuantity = null;

    #[Column('min_cart_items')]
    public ?int $minCartItems = null;

    #[Column('min_cart_price')]
    public ?float $minCartPrice = null;

    #[Column('free_shipping')]
    #[Cast('bool', 'int')]
    public bool $freeShipping = false;

    #[Column('accumulate')]
    #[Cast('bool', 'int')]
    public bool $accumulate = false;

    #[Column('method')]
    #[Cast(DiscountMethod::class)]
    public DiscountMethod $method {
        set(DiscountMethod|string $value) => $this->method = DiscountMethod::wrap($value);
    }

    #[Column('apply_to')]
    #[Cast(DiscountApplyTo::class)]
    public DiscountApplyTo $applyTo {
        set(DiscountApplyTo|string $value) => $this->applyTo = DiscountApplyTo::wrap($value);
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
}

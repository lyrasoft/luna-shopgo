<?php

/**
* Part of starter project.
*
* @copyright  Copyright (C) 2021 __ORGANIZATION__.
* @license    __LICENSE__
*/

declare(strict_types=1);

namespace App\Entity;

use Windwalker\ORM\Attributes\AutoIncrement;
use Windwalker\Core\DateTime\Chronos;
use Windwalker\ORM\Attributes\CastNullable;
use Windwalker\ORM\Attributes\Cast;
use Windwalker\ORM\Cast\JsonCast;
use Windwalker\ORM\Attributes\Column;
use Windwalker\ORM\Attributes\EntitySetup;
use Windwalker\ORM\Attributes\PK;
use Windwalker\ORM\Attributes\Table;
use Windwalker\ORM\EntityInterface;
use Windwalker\ORM\EntityTrait;
use Windwalker\ORM\Metadata\EntityMetadata;

/**
* The DiscountUsage class.
*/
#[Table('discount_usages', 'discount_usage')]
class DiscountUsage implements EntityInterface
{
    use EntityTrait;
    #[Column('id'), PK, AutoIncrement]
    protected ?int $id = null;
    #[Column('discount_id')]
    protected int $discountId = 0;
    #[Column('order_id')]
    protected int $orderId = 0;
    #[Column('type')]
    protected string $type = '';
    #[Column('user_id')]
    protected int $userId = 0;
    #[Column('used_at')]
    #[CastNullable(Chronos::class)]
    protected ?Chronos $usedAt = null;
    #[Column('params')]
    #[Cast(JsonCast::class)]
    protected array $params = array();

    #[EntitySetup]
    public static function setup(EntityMetadata $metadata): void
    {
        //
    }
    public function getId() : ?int
    {
        return $this->id;
    }
    public function setId(?int $id) : static
    {
        $this->id = $id;
        return $this;
    }
    public function getDiscountId() : int
    {
        return $this->discountId;
    }
    public function setDiscountId(int $discountId) : static
    {
        $this->discountId = $discountId;
        return $this;
    }
    public function getOrderId() : int
    {
        return $this->orderId;
    }
    public function setOrderId(int $orderId) : static
    {
        $this->orderId = $orderId;
        return $this;
    }
    public function getType() : string
    {
        return $this->type;
    }
    public function setType(string $type) : static
    {
        $this->type = $type;
        return $this;
    }
    public function getUserId() : int
    {
        return $this->userId;
    }
    public function setUserId(int $userId) : static
    {
        $this->userId = $userId;
        return $this;
    }
    public function getUsedAt() : ?Chronos
    {
        return $this->usedAt;
    }
    public function setUsedAt(\DateTimeInterface|string|null $usedAt) : static
    {
        $this->usedAt = Chronos::wrapOrNull($usedAt);
        return $this;
    }
    public function getParams() : array
    {
        return $this->params;
    }
    public function setParams(array $params) : static
    {
        $this->params = $params;
        return $this;
    }
}

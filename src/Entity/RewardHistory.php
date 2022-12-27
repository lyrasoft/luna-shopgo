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
use Lyrasoft\Luna\Attributes\Author;
use Windwalker\ORM\Attributes\Column;
use Windwalker\ORM\Attributes\EntitySetup;
use Windwalker\ORM\Attributes\PK;
use Windwalker\ORM\Attributes\Table;
use Windwalker\ORM\EntityInterface;
use Windwalker\ORM\EntityTrait;
use Windwalker\ORM\Metadata\EntityMetadata;

/**
* The RewardHistory class.
*/
#[Table('reward_histories', 'reward_history')]
class RewardHistory implements EntityInterface
{
    use EntityTrait;
    #[Column('id'), PK, AutoIncrement]
    protected ?int $id = null;
    #[Column('user_id')]
    protected int $userId = 0;
    #[Column('order_id')]
    protected int $orderId = 0;
    #[Column('action')]
    protected string $action = '';
    #[Column('points')]
    protected float $points = 0.0;
    #[Column('remain')]
    protected float $remain = 0.0;
    #[Column('ratio')]
    protected string $ratio = '';
    #[Column('time')]
    #[CastNullable(Chronos::class)]
    protected ?Chronos $time = null;
    #[Column('note')]
    protected string $note = '';
    #[Column('created_by')]
    #[Author]
    protected int $createdBy = 0;

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
    public function getUserId() : int
    {
        return $this->userId;
    }
    public function setUserId(int $userId) : static
    {
        $this->userId = $userId;
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
    public function getAction() : string
    {
        return $this->action;
    }
    public function setAction(string $action) : static
    {
        $this->action = $action;
        return $this;
    }
    public function getPoints() : float
    {
        return $this->points;
    }
    public function setPoints(float $points) : static
    {
        $this->points = $points;
        return $this;
    }
    public function getRemain() : float
    {
        return $this->remain;
    }
    public function setRemain(float $remain) : static
    {
        $this->remain = $remain;
        return $this;
    }
    public function getRatio() : string
    {
        return $this->ratio;
    }
    public function setRatio(string $ratio) : static
    {
        $this->ratio = $ratio;
        return $this;
    }
    public function getTime() : ?Chronos
    {
        return $this->time;
    }
    public function setTime(\DateTimeInterface|string|null $time) : static
    {
        $this->time = Chronos::wrapOrNull($time);
        return $this;
    }
    public function getNote() : string
    {
        return $this->note;
    }
    public function setNote(string $note) : static
    {
        $this->note = $note;
        return $this;
    }
    public function getCreatedBy() : int
    {
        return $this->createdBy;
    }
    public function setCreatedBy(int $createdBy) : static
    {
        $this->createdBy = $createdBy;
        return $this;
    }
}

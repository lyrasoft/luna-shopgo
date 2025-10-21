<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Entity;

use Lyrasoft\ShopGo\Service\OrderStateService;
use Lyrasoft\Luna\Attributes\Slugify;
use Windwalker\ORM\Attributes\AutoIncrement;
use Windwalker\ORM\Attributes\Cast;
use Windwalker\ORM\Attributes\Column;
use Windwalker\ORM\Attributes\EntitySetup;
use Windwalker\ORM\Attributes\PK;
use Windwalker\ORM\Attributes\Table;
use Windwalker\ORM\EntityInterface;
use Windwalker\ORM\EntityTrait;
use Windwalker\ORM\Metadata\EntityMetadata;

/**
 * The OrderState class.
 */
#[Table('order_states', 'order_state')]
#[\AllowDynamicProperties]
class OrderState implements EntityInterface
{
    use EntityTrait;

    #[Column('id'), PK, AutoIncrement]
    public ?int $id = null;

    #[Column('title')]
    public string $title = '';

    #[Column('alias')]
    #[Slugify]
    public string $alias = '';

    #[Column('default')]
    #[Cast('bool', 'int')]
    public bool $default = false;

    #[Column('color')]
    public string $color = '';

    #[Column('image')]
    public string $image = '';

    #[Column('notice')]
    #[Cast('bool', 'int')]
    public bool $notice = false;

    #[Column('attach_invoice')]
    #[Cast('bool', 'int')]
    public bool $attachInvoice = false;

    #[Column('shipped')]
    #[Cast('bool', 'int')]
    public bool $shipped = false;

    #[Column('paid')]
    #[Cast('bool', 'int')]
    public bool $paid = false;

    #[Column('returned')]
    #[Cast('bool', 'int')]
    public bool $returned = false;

    #[Column('done')]
    #[Cast('bool', 'int')]
    public bool $done = false;

    #[Column('cancel')]
    #[Cast('bool', 'int')]
    public bool $cancel = false;

    #[Column('rollback')]
    #[Cast('bool', 'int')]
    public bool $rollback = false;

    #[Column('ordering')]
    public int $ordering = 0;

    #[EntitySetup]
    public static function setup(EntityMetadata $metadata): void
    {
        //
    }

    public function getContrastColor(int $sep = 200): string
    {
        return OrderStateService::colorToContrast($this->color, $sep);
    }

    public function getColorCSS(int $sep = 200): string
    {
        return OrderStateService::colorToCSS($this->color, $sep);
    }
}

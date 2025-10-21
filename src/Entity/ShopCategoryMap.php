<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Entity;

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
 * The ShopCategoryMap class.
 */
#[Table('shop_category_maps', 'shop_category_map')]
#[\AllowDynamicProperties]
class ShopCategoryMap implements EntityInterface
{
    use EntityTrait;

    #[Column('id'), PK, AutoIncrement]
    public ?int $id = null;

    #[Column('type')]
    public string $type = '';

    #[Column('target_id')]
    public int $targetId = 0;

    #[Column('category_id')]
    public int $categoryId = 0;

    #[Column('primary')]
    #[Cast('bool', 'int')]
    public bool $primary = false;

    #[Column('ordering')]
    public int $ordering = 0;

    #[EntitySetup]
    public static function setup(EntityMetadata $metadata): void
    {
        //
    }
}

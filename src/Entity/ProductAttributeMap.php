<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Entity;

use Windwalker\ORM\Attributes\AutoIncrement;
use Windwalker\ORM\Attributes\Column;
use Windwalker\ORM\Attributes\EntitySetup;
use Windwalker\ORM\Attributes\PK;
use Windwalker\ORM\Attributes\Table;
use Windwalker\ORM\EntityInterface;
use Windwalker\ORM\EntityTrait;
use Windwalker\ORM\Metadata\EntityMetadata;

/**
 * The ProductAttributeMap class.
 */
#[Table('product_attribute_maps', 'product_attribute_map')]
#[\AllowDynamicProperties]
class ProductAttributeMap implements EntityInterface
{
    use EntityTrait;

    #[Column('id'), PK, AutoIncrement]
    public ?int $id = null;

    #[Column('product_id')]
    public int $productId = 0;

    #[Column('attribute_id')]
    public int $attributeId = 0;

    #[Column('key')]
    public string $key = '';

    #[Column('value')]
    public string $value = '';

    #[Column('locale')]
    public string $locale = '*';

    #[EntitySetup]
    public static function setup(EntityMetadata $metadata): void
    {
        //
    }
}

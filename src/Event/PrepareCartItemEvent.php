<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Event;

use Lyrasoft\ShopGo\Cart\CartItem;
use Lyrasoft\ShopGo\Entity\Product;
use Lyrasoft\ShopGo\Entity\ProductVariant;
use Windwalker\Event\AbstractEvent;
use Windwalker\Event\BaseEvent;

/**
 * The PrepareCartItemEvent class.
 */
class PrepareCartItemEvent extends BaseEvent
{
    public function __construct(
        public CartItem $cartItem,
        public Product $product,
        public ProductVariant $mainVariant,
        public ProductVariant $variant,
        public array $storageItem = [],
        public bool $forUpdate = false,
        public array $options = [],
    ) {
    }
}

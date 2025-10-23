<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Event;

use Lyrasoft\ShopGo\Cart\CartItem;
use Lyrasoft\ShopGo\Cart\Price\PriceSet;
use Lyrasoft\ShopGo\Data\Contract\ProductPricingInterface;
use Lyrasoft\ShopGo\Data\Traits\ProductPricingTrait;
use Lyrasoft\ShopGo\Entity\Product;
use Lyrasoft\ShopGo\Entity\ProductVariant;
use Windwalker\Event\BaseEvent;

/**
 * The PrepareProductTotalsEvent class.
 */
class PrepareProductPricesEvent extends BaseEvent implements ProductPricingInterface
{
    use ProductPricingTrait;

    public const string PRODUCT_VIEW = 'product_view';

    public const string CART = 'cart';

    public const string ORDER = 'order';

    public function __construct(
        public ?CartItem $cartItem,
        string $context,
        Product $product,
        ProductVariant $variant,
        ProductVariant $mainVariant,
        PriceSet $priceSet,
        array $appliedDiscounts = [],
    ) {
        $this->product = $product;
        $this->variant = $variant;
        $this->mainVariant = $mainVariant;
        $this->priceSet = $priceSet;
        $this->appliedDiscounts = $appliedDiscounts;
        $this->context = $context;
    }
}

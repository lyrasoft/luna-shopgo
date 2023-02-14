<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Service;

use Lyrasoft\ShopGo\Entity\AdditionalPurchaseAttachment;
use Lyrasoft\ShopGo\Entity\Product;
use Lyrasoft\ShopGo\Entity\ProductVariant;

/**
 * The AdditionalPurchaseService class.
 */
class AdditionalPurchaseService
{
    public function prepareVariantView(
        ProductVariant $variant,
        Product $product,
        AdditionalPurchaseAttachment $attachment
    ): ProductVariant {
        $priceSet = $variant->getPriceSet();

        $newPrice = PricingService::pricingByMethod(
            $priceSet['final'],
            $attachment->getPrice(),
            $attachment->getMethod()
        );

        $priceSet['final']->setPrice((string) $newPrice);

        return $variant;
    }
}

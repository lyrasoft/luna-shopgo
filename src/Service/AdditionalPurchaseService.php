<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Service;

use Lyrasoft\ShopGo\Entity\AdditionalPurchase;
use Lyrasoft\ShopGo\Entity\AdditionalPurchaseAttachment;
use Lyrasoft\ShopGo\Entity\AdditionalPurchaseTarget;
use Lyrasoft\ShopGo\Entity\Product;
use Lyrasoft\ShopGo\Entity\ProductVariant;
use Windwalker\Data\Collection;
use Windwalker\ORM\ORM;
use Windwalker\Query\Query;

use function Windwalker\chronos;

/**
 * The AdditionalPurchaseService class.
 */
class AdditionalPurchaseService
{
    public function __construct(protected ORM $orm)
    {
    }

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

    /**
     * @param  int  $productId
     *
     * @return  Collection<ProductVariant>
     */
    public function getAvailableVariants(int $productId): Collection
    {
        $now = chronos();

        return $this->orm->from(ProductVariant::class)
            ->leftJoin(Product::class)
            ->leftJoin(
                AdditionalPurchaseAttachment::class,
                'attachment',
                'attachment.variant_id',
                'product_variant.id'
            )
            ->leftJoin(
                AdditionalPurchase::class,
                'ap',
                'ap.id',
                'attachment.additional_purchase_id'
            )
            ->whereExists(
                fn (Query $query) => $query->from(AdditionalPurchaseTarget::class, 'target')
                    ->whereRaw('additional_purchase_id = attachment.additional_purchase_id')
                    ->whereRaw('target.product_id = %a', $productId)
            )
            ->where('ap.state', 1)
            ->orWhere(
                function (Query $query) use ($now) {
                    $query->where('ap.publish_up', null);
                    $query->where('ap.publish_up', '<', $now);
                }
            )
            ->orWhere(
                function (Query $query) use ($now) {
                    $query->where('ap.publish_down', null);
                    $query->where('ap.publish_down', '>=', $now);
                }
            )
            ->where('product.state', 1)
            ->orWhere(
                function (Query $query) use ($now) {
                    $query->where('product.publish_up', null);
                    $query->where('product.publish_up', '<', $now);
                }
            )
            ->orWhere(
                function (Query $query) use ($now) {
                    $query->where('product.publish_down', null);
                    $query->where('product.publish_down', '>=', $now);
                }
            )
            ->groupByJoins()
            ->all(ProductVariant::class);
    }
}

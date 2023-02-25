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
use Windwalker\Core\Form\Exception\ValidateFailException;
use Windwalker\Data\Collection;
use Windwalker\ORM\ORM;
use Windwalker\Query\Query;

use Windwalker\Utilities\Cache\InstanceCacheTrait;

use function Windwalker\chronos;

/**
 * The AdditionalPurchaseService class.
 */
class AdditionalPurchaseService
{
    use InstanceCacheTrait;

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

    /**
     * @param  AdditionalPurchaseAttachment  $attachment
     * @param  Product                       $targetProduct
     * @param  bool                          $forUpdate
     *
     * @return  array{ 0: Product, 1: ProductVariant, 2: AdditionalPurchase }
     */
    public function validateAttachment(
        AdditionalPurchaseAttachment $attachment,
        Product $targetProduct,
        bool $forUpdate = false
    ): array {
        $ap = $this->getAdditionalPurchase($attachment->getAdditionalPurchaseId());

        $now = chronos();

        if ($ap->getPublishUp() !== null && $ap->getPublishUp() > $now) {
            throw new ValidateFailException('Additional Purchase not started yet.');
        }

        if ($ap->getPublishDown() !== null && $ap->getPublishDown() < $now) {
            throw new ValidateFailException('Additional Purchase has ended.');
        }

        $targets = $this->getTargets($ap->getId());

        $targetIds = $targets->column('productId');

        if (!$targetIds->contains($targetProduct->getId())) {
            throw new ValidateFailException('The target product not in additional purchase targets');
        }

        $variant = $this->orm->from(ProductVariant::class)
            ->where('id', $attachment->getVariantId())
            ->tapIf(
                $forUpdate,
                fn (Query $query) => $query->forUpdate()
            )
            ->get(ProductVariant::class);

        if (!$variant) {
            throw new \RuntimeException('Variant: ' . $attachment->getVariantId() . ' not found.');
        }

        $product = $this->getVariantProduct($variant->getProductId());

        return [$product, $variant, $ap];
    }

    protected function getVariantProduct(int $id): Product
    {
        return $this->cacheStorage['product.' . $id] ??= $this->orm->mustFindOne(Product::class, $id);
    }

    /**
     * @param  int  $apId
     *
     * @return  Collection<AdditionalPurchaseTarget>
     */
    public function getTargets(int $apId): Collection
    {
        return $this->cacheStorage['ap.targets.' . $apId]
            ??= $this->orm->findList(
                AdditionalPurchaseTarget::class,
                [
                    'additional_purchase_id' => $apId
                ]
            )->all();
    }

    public function getAdditionalPurchase(int $id): AdditionalPurchase
    {
        return $this->cacheStorage['ap.' . $id] ??= $this->orm->mustFindOne(AdditionalPurchase::class, $id);
    }
}

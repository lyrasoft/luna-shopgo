<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Service;

use Lyrasoft\ShopGo\Cart\CartData;
use Lyrasoft\ShopGo\Cart\CartItem;
use Lyrasoft\ShopGo\Cart\Price\PriceSet;
use Lyrasoft\ShopGo\Data\ListOption;
use Lyrasoft\ShopGo\Data\ListOptionCollection;
use Lyrasoft\ShopGo\Entity\Product;
use Lyrasoft\ShopGo\Entity\ProductFeature;
use Lyrasoft\ShopGo\Entity\ProductVariant;
use Lyrasoft\ShopGo\Event\PrepareProductPricesEvent;
use Lyrasoft\ShopGo\ShopGoPackage;
use Windwalker\Data\Collection;
use Windwalker\ORM\ORM;

use function Windwalker\collect;

/**
 * The VariantService class.
 */
class VariantService
{
    public function __construct(protected ORM $orm, protected ShopGoPackage $shopGo)
    {
    }

    public function prepareVariantView(ProductVariant $variant, ?Product $product = null): ProductVariant
    {
        $product ??= $this->orm->mustFindOne(Product::class, $variant->getProductId());

        $priceSet = $variant->getPriceSet();

        if ($product->getOriginPrice()) {
            $priceSet['origin']->setPrice((string) $product->getOriginPrice());
        }

        // $mainVariant = $item->getMainVariant();
        // Todo: @event PrepareProductPrices
        $priceSet = $this->computeProductPriceSet(
            PrepareProductPricesEvent::PRODUCT_VIEW,
            $product,
            $variant,
            $variant,
            $priceSet,
        );

        $variant->setPriceSet($priceSet);

        // Todo: @event PrepareProductDiscountsInformation
        // Todo: @event PrepareProductInformation

        return $variant;
    }

    /**
     * @param  string          $context
     * @param  Product         $product
     * @param  ProductVariant  $mainVariant
     * @param  ProductVariant  $variant
     * @param  PriceSet        $priceSet
     * @param  CartItem|null   $cartItem
     *
     * @return PriceSet
     */
    public function computeProductPriceSet(
        string $context,
        Product $product,
        ProductVariant $mainVariant,
        ProductVariant $variant,
        PriceSet $priceSet,
        ?CartItem $cartItem = null,
    ): PriceSet {
        $event = (new PrepareProductPricesEvent())
            ->setContext($context)
            ->setProduct($product)
            ->setMainVariant($mainVariant)
            ->setVariant($variant)
            ->setPriceSet($priceSet)
            ->setCartItem($cartItem)
            ->setAppliedDiscounts($cartItem?->getDiscounts() ?? []);

        /** @var PrepareProductPricesEvent $event */
        $event = $this->shopGo->emit($event);

        // $pricing = $event->getPriceSet();
        //
        // foreach ($pricing as $price) {
        //     $priceSet->set($price);
        // }

        // $priceSet->set(
        //     $event->getBasePrice()
        //         ->plusMultiple(...$pricing)
        //         ->withName('final')
        // );

        $cartItem?->setDiscounts($event->getAppliedDiscounts());
        $cartItem?->setPriceSet($priceSet);

        return $priceSet;
    }

    public static function isOutOfStock(ProductVariant $variant, Product $product, int $quantity = 1): bool
    {
        if (!$variant->isSubtract()) {
            return false;
        }

        return static::getAvailableQuantity($variant, $product) < $quantity;
    }

    public static function getAvailableQuantity(ProductVariant $variant, Product $product): int
    {
        $safeStock = $product->getSafeStock();

        return max($variant->getStockQuantity() - $safeStock, 0);
    }

    /**
     * @param  array<ListOption|array>  $options
     *
     * @return  string
     */
    public static function hashByOptions(array|ListOptionCollection $options): string
    {
        $values = ListOptionCollection::wrap($options)
            ->as(Collection::class)
            ->map(static fn ($option) => $option['uid'])
            ->dump();

        return static::hash($values);
    }

    public static function hash(array $values, ?string &$seed = null): string
    {
        sort($values);

        return md5($seed = implode(':', $values));
    }

    /**
     * @param  array  $featureOptGroups
     * @param  array  $parentGroup
     *
     * @return  array<array<array{ text: string, value: string }>>
     */
    public function sortOptionsGroup(array $featureOptGroups, array $parentGroup = []): array
    {
        $currentOptions = array_pop($featureOptGroups);

        $returnValue = [];

        foreach ($currentOptions as $option) {
            $group = $parentGroup;

            $group[] = $option;

            if (\count($featureOptGroups)) {
                $returnValue[] = $this->sortOptionsGroup($featureOptGroups, $group);
            } else {
                $returnValue[] = [$group];
            }
        }

        return array_merge(...$returnValue);
    }

    /**
     * @param  Product  $product
     *
     * @return  Collection<ProductFeature>
     */
    public function findFeaturesFromProduct(Product $product): Collection
    {
        $variants = $this->orm->from(ProductVariant::class)
            ->where('product_id', $product->getId())
            ->where('primary', 0)
            ->all(ProductVariant::class);

        $featureIds = [];
        $options = [];

        // Find options
        /** @var ProductVariant $variant */
        foreach ($variants as $variant) {
            /** @var ListOption $option */
            foreach ($variant->getOptions() as $option) {
                if ($option->getParentId()) {
                    $featureIds[] = $option->getParentId();
                    $options[$option->getUid()] = $option;
                }
            }
        }

        $optionUids = array_keys($options);
        $featureIds = array_unique($featureIds);

        // Find features
        $features = $this->orm->from(ProductFeature::class)
            ->whereIn('id', $featureIds ?: [0])
            ->all(ProductFeature::class);

        // Only keep selected options
        /** @var ProductFeature $feature */
        foreach ($features as $feature) {
            /** @var ListOptionCollection $options */
            $options = $feature->getOptions();

            $options = $options->filter(
                function (ListOption $option) use ($optionUids) {
                    return in_array($option->getUid(), $optionUids, true);
                }
            );

            $feature->setOptions($options);
        }

        return $features;
    }
}

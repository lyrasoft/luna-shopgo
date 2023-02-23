<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Shipping;

use Brick\Math\BigDecimal;
use Lyrasoft\Luna\Entity\Category;
use Lyrasoft\ShopGo\Cart\CartData;
use Lyrasoft\ShopGo\Cart\Price\PriceObject;
use Lyrasoft\ShopGo\Entity\Location;
use Lyrasoft\ShopGo\Entity\ProductVariant;
use Lyrasoft\ShopGo\Field\ShippingPricingField;
use Lyrasoft\ShopGo\Traits\CurrencyAwareTrait;
use Unicorn\Field\ButtonRadioField;
use Unicorn\Field\SwitcherField;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Form\Field\NumberField;
use Windwalker\Form\Form;
use Windwalker\ORM\ORM;

/**
 * Trait PriceRangeTrait
 */
trait PriceRangeTrait
{
    use CurrencyAwareTrait;
    use TranslatorTrait;

    protected function registerPricingForm(Form $form): void
    {
        $form->fieldset('pricing')
            ->title($this->trans('shopgo.shipping.fieldset.pricing'))
            ->register(
                fn(Form $form) => $form->ns('pricing', function (Form $form) {
                    $form->add('free', SwitcherField::class)
                        ->label('shopgo.shipping.field.free')
                        ->color('primary')
                        ->circle(true);

                    $form->add('flat_fee', NumberField::class)
                        ->label('shopgo.shipping.field.flat.fee')
                        ->min(0, false)
                        ->step($this->getMainCurrency()->getInputStep());

                    $form->add('depends_on', ButtonRadioField::class)
                        ->label('shopgo.shipping.field.depends.on')
                        ->option($this->trans('shopgo.shipping.depend.on.option.price'), static::DEPENDS_ON_PRICE)
                        ->option($this->trans('shopgo.shipping.depend.on.option.weight'), static::DEPENDS_ON_WEIGHT)
                        ->defaultValue('price');

                    $form->add('compute_unit', ButtonRadioField::class)
                        ->label('shopgo.shipping.field.unit')
                        ->option($this->trans('shopgo.shipping.unit.option.per.order'), static::COMPUTE_UNIT_PER_ORDER)
                        ->option($this->trans('shopgo.shipping.unit.option.per.item'), static::COMPUTE_UNIT_PER_ITEM)
                        ->defaultValue('order');

                    $form->add('range', ShippingPricingField::class)
                        ->label('shopgo.shipping.field.range');
                })
            );
    }

    public function getShippingFeeComputer(CartData $cartData, PriceObject $total): \Closure
    {
        return function (ORM $orm) use ($total, $cartData) {
            $pricing = $this->getPricing();

            $fee = BigDecimal::of(0);

            if ($pricing['free'] ?? false) {
                return $fee;
            }

            $location = $cartData->getLocation();

            $flatFee = $pricing['flat_fee'] ?? '';
            $depends = $pricing['depends_no'] ?? static::DEPENDS_ON_PRICE;
            $computeUnit = $pricing['compute_unit'] ?? static::COMPUTE_UNIT_PER_ORDER;
            $range = $pricing['range'] ?? [];
            $cartItems = $cartData->getItems();

            // Find matched location
            $matchedPricing = null;
            $locCategories = $range['locationCategories'] ?? [];

            if ($location) {
                foreach ($locCategories as $locCategory) {
                    $found = $orm->from(Location::class)
                        ->leftJoin(Category::class)
                        ->where('location.lft', '<=', $location->getLft())
                        ->where('location.rgt', '>=', $location->getRgt())
                        ->where('category.id', $locCategory['id'] ?? 0)
                        ->get();

                    if ($found) {
                        $matchedPricing = $locCategory['pricing'] ?? [];
                        break;
                    }
                }

                if ($matchedPricing === null) {
                    foreach ($range['locations'] ?? [] as $loc) {
                        $found = $orm->from(Location::class)
                            ->where('location.lft', '<=', $location->getLft())
                            ->where('location.rgt', '>=', $location->getRgt())
                            ->where('location.id', $loc['id'] ?? 0)
                            ->get();

                        if ($found) {
                            $matchedPricing = $loc['pricing'] ?? [];
                            break;
                        }
                    }
                }
            }

            if ($matchedPricing === null) {
                $matchedPricing = $range['global']['pricing'] ?? [];
            }

            if ($computeUnit === static::COMPUTE_UNIT_PER_ITEM) {
                // Per Item
                foreach ($cartItems as $cartItem) {
                    if ($flatFee) {
                        $fee = $fee->plus($flatFee);
                    } else {
                        // Get depends value
                        if ($depends === static::DEPENDS_ON_PRICE) {
                            $value = BigDecimal::of((string) $cartItem->getPriceSet()['final_total']);
                        } else {
                            /** @var ProductVariant $variant */
                            $variant = $cartItem->getVariant()->getData();

                            $value = BigDecimal::of($variant->getDimension()->getWeight())
                                ->multipliedBy($cartItem->getQuantity());
                        }

                        $itemFee = BigDecimal::of(0);

                        // Loop ranges
                        foreach ($matchedPricing as $pricingSegment) {
                            $threshold = $pricingSegment['threshold'] ?? 0;

                            if ($value->isGreaterThan($threshold)) {
                                $itemFee = BigDecimal::of($pricingSegment['fee'] ?? 0);
                                $priceSet = $cartItem->getPriceSet();
                                $priceSet->add(
                                    'sipping_fee',
                                    $itemFee,
                                    '運費',
                                    [
                                        'id' => $this->getData()->getId(),
                                        'alias' => $this->getData()->getAlias(),
                                        'title' => $this->getData()->getTitle(),
                                        'location_id' => $location?->getId(),
                                    ]
                                );
                                $cartItem->setPriceSet($priceSet);
                            }
                        }

                        $fee = $fee->plus($itemFee);
                    }
                }
            } else {
                // Per Order
                if ($flatFee) {
                    $fee = $fee->plus($flatFee);
                } else {
                    // Get depends value
                    if ($depends === static::DEPENDS_ON_PRICE) {
                        $value = BigDecimal::of((string) $total);
                    } else {
                        $value = BigDecimal::of(0);

                        foreach ($cartData->getItems() as $cartItem) {
                            /** @var ProductVariant $variant */
                            $variant = $cartItem->getVariant()->getData();

                            $value = $value->plus(
                                BigDecimal::of($variant->getDimension()->getWeight())
                                    ->multipliedBy($cartItem->getQuantity())
                            );
                        }
                    }

                    // Loop ranges
                    foreach ($matchedPricing as $pricingSegment) {
                        $threshold = $pricingSegment['threshold'] ?? 0;

                        if ($value->isGreaterThan($threshold)) {
                            $fee = BigDecimal::of($pricingSegment['fee'] ?? 0);
                        }
                    }
                }
            } // End computing

            if ($fee->isGreaterThan(0)) {
                $totals = $cartData->getTotals();
                $totals->add(
                    'shipping_fee',
                    $fee,
                    '運費',
                    [
                        'id' => $this->getData()->getId(),
                        'alias' => $this->getData()->getAlias(),
                        'title' => $this->getData()->getTitle(),
                        'location_id' => $location?->getId(),
                    ]
                );
            }

            return $fee;
        };
    }
}

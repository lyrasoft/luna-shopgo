<?php

/**
 * Part of toolstool project.
 *
 * @copyright  Copyright (C) 2022 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Entity\Traits;

use Brick\Math\Exception\MathException;
use Lyrasoft\ShopGo\Cart\Price\PriceObject;
use Lyrasoft\ShopGo\Cart\Price\PriceSet;
use Lyrasoft\ShopGo\Entity\Discount;
use Windwalker\Data\Collection;

use function Windwalker\collect;

/**
 * Trait ProductVariantTrait
 */
trait ProductVariantTrait
{
    public PriceSet $priceSet {
        get => $this->priceSet ??= $this->preparePriceSet();
    }

    /**
     * @var Collection<Discount>
     */
    public Collection $applyDiscounts {
        get => $this->applyDiscounts ??= collect();
    }

    protected function preparePriceSet(): PriceSet
    {
        $priceSet = new PriceSet();

        $priceSet->set(
            new PriceObject(
                'origin',
                (string) $this->price
            )
        );

        $priceSet->set(
            new PriceObject(
                'base',
                (string) $this->price
            )
        );

        $priceSet->set(
            new PriceObject(
                'final',
                (string) $this->price
            )
        );

        return $priceSet;
    }
}

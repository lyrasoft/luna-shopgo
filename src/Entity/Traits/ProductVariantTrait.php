<?php

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
        set(PriceSet|array $value) => PriceSet::wrap($value);
    }

    /**
     * @var Collection<Discount>
     */
    public Collection $applyDiscounts {
        get => $this->applyDiscounts ??= collect();
        set(Collection|array $value) => collect($value);
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

<?php

/**
 * Part of toolstool project.
 *
 * @copyright  Copyright (C) 2022 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Entity\Traits;

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
    protected ?PriceSet $priceSet = null;

    /**
     * @var Collection<Discount>|null
     */
    protected ?Collection $applyDiscounts = null;

    /**
     * @return PriceSet
     */
    public function getPriceSet(): PriceSet
    {
        if (!$this->priceSet) {
            $this->priceSet = new PriceSet();

            $this->priceSet->set(
                new PriceObject(
                    'origin',
                    (string) $this->getPrice()
                )
            );

            $this->priceSet->set(
                new PriceObject(
                    'base',
                    (string) $this->getPrice()
                )
            );

            $this->priceSet->set(
                new PriceObject(
                    'final',
                    (string) $this->getPrice()
                )
            );
        }

        return $this->priceSet;
    }

    /**
     * @param  PriceSet|null  $priceSet
     *
     * @return  static  Return self to support chaining.
     */
    public function setPriceSet(?PriceSet $priceSet): static
    {
        $this->priceSet = $priceSet;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getApplyDiscounts(): Collection
    {
        return $this->applyDiscounts ??= collect();
    }

    /**
     * @param  Collection|null  $applyDiscounts
     *
     * @return  static  Return self to support chaining.
     */
    public function setApplyDiscounts(?Collection $applyDiscounts): static
    {
        $this->applyDiscounts = $applyDiscounts;

        return $this;
    }
}

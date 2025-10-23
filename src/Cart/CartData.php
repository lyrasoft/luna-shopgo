<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Cart;

use Lyrasoft\ShopGo\Cart\Price\PriceSet;
use Lyrasoft\ShopGo\Entity\Discount;
use Lyrasoft\ShopGo\Entity\Location;
use Lyrasoft\ShopGo\Entity\ProductVariant;
use Lyrasoft\ShopGo\Entity\Shipping;
use Windwalker\Data\Collection;
use Windwalker\Data\RecordInterface;
use Windwalker\Data\RecordTrait;

use function Windwalker\collect;

/**
 * The CartData class.
 */
class CartData implements RecordInterface
{
    use RecordTrait;

    /**
     * @var Collection<CartItem>
     */
    public Collection $items {
        set(Collection|array $value) => $this->items = collect($value);
    }

    /**
     * @var Collection<Discount>
     */
    public Collection $discounts {
        set(Collection|array $value) => $this->discounts = collect($value);
    }

    /**
     * @var Collection
     */
    public Collection $coupons {
        set(Collection|array $value) => $this->coupons = collect($value);
    }

    public function __construct(
        Collection|array $items = new Collection(),
        Collection|array $discounts = new Collection(),
        Collection|array $coupons = new Collection(),
        public PriceSet $totals = new PriceSet(),
        public ?Location $location = null,
        public ?Shipping $shipping = null,
        public array $params = []
    ) {
        $this->items = $items;
        $this->discounts = $discounts;
        $this->coupons = $coupons;
    }

    /**
     * @param  bool  $onlyChecked
     *
     * @return Collection<CartItem>
     */
    public function getItems(bool $onlyChecked = false): Collection
    {
        $items = $this->items;

        if ($onlyChecked) {
            $items = $items->filter(
                fn(CartItem $item) => $item->isChecked()
            );
        }

        return $items;
    }

    /**
     * @return Collection<CartItem>
     */
    public function getCheckedItems(): Collection
    {
        return $this->getItems(true);
    }

    /**
     * @param  Collection<CartItem>  $items
     *
     * @return  static  Return self to support chaining.
     */
    public function setItems(Collection $items): static
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @param  bool  $onlyChecked
     * @param  bool  $includeAttachments
     *
     * @return  array<int, int>
     */
    public function getTotalQuantities(bool $onlyChecked = false, bool $includeAttachments = false): array
    {
        $quantities = [];

        if ($onlyChecked) {
            $items = $this->getCheckedItems();
        } else {
            $items = $this->getItems();
        }

        foreach ($items as $item) {
            /** @var ProductVariant $variant */
            $variant = $item->variant->getData();
            $quantity = $quantities[$variant->id] ?? 0;

            $quantity += $item->quantity;

            $quantities[$variant->id] = $quantity;

            if ($includeAttachments) {
                foreach ($item->attachments as $attachment) {
                    /** @var ProductVariant $variant */
                    $variant = $attachment->variant->getData();
                    $quantity = $quantities[$variant->id] ?? 0;

                    $quantity += ($attachment->quantity * $item->getQuantity());

                    $quantities[$variant->id] = $quantity;
                }
            }
        }

        return $quantities;
    }
}

<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Field;

use Lyrasoft\ShopGo\Entity\ProductVariant;
use Unicorn\Field\SqlListField;
use Windwalker\DOM\DOMElement;
use Windwalker\Query\Query;

/**
 * The ProductVariantListField class.
 */
class ProductVariantListField extends SqlListField
{
    protected ?string $table = ProductVariant::class;

    protected int $productId;

    /**
     * @return int
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * @param  int  $productId
     *
     * @return  static  Return self to support chaining.
     */
    public function setProductId(int $productId): static
    {
        $this->productId = $productId;

        return $this;
    }

    protected function prepareQuery(Query $query): void
    {
        parent::prepareQuery($query);

        $query->where('product_id', $this->getProductId());
    }

    public function createItemOption(object $item): DOMElement
    {
        $option = parent::createItemOption($item);

        if ($item->sku) {
            $option->textContent = $option->textContent .= ' - #' . $item->sku;
        }

        return $option;
    }

    /**
     * prepareInput
     *
     * @param  DOMElement  $input
     *
     * @return  DOMElement
     */
    public function prepareInput(DOMElement $input): DOMElement
    {
        return $input;
    }

    /**
     * getAccessors
     *
     * @return  array
     */
    protected function getAccessors(): array
    {
        return array_merge(
            parent::getAccessors(),
            []
        );
    }
}

<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Field;

use Lyrasoft\ShopGo\Entity\Currency;
use Unicorn\Field\SqlListField;
use Windwalker\DOM\HTMLElement;

/**
 * The CurrencyListField class.
 */
class CurrencyListField extends SqlListField
{
    protected ?string $table = Currency::class;

    /**
     * prepareInput
     *
     * @param  HTMLElement  $input
     *
     * @return  HTMLElement
     */
    public function prepareInput(HTMLElement $input): HTMLElement
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

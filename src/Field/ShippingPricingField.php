<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Field;

use Lyrasoft\ShopGo\Entity\Location;
use Unicorn\Field\LayoutFieldTrait;
use Windwalker\DI\Attributes\Inject;
use Windwalker\DOM\DOMElement;
use Windwalker\Form\Field\AbstractField;
use Windwalker\ORM\NestedSetMapper;
use Windwalker\ORM\ORM;

/**
 * The ShippingPricingField class.
 */
class ShippingPricingField extends AbstractField
{
    use LayoutFieldTrait;

    #[Inject]
    protected ORM $orm;

    public function getDefaultLayout(): string
    {
        return 'field.shipping-pricing';
    }

    public function prepareInput(DOMElement $input): DOMElement
    {
        return $input;
    }

    public function buildFieldElement(DOMElement $input, array $options = []): string|DOMElement
    {
        $field = $this;

        $values = $this->getPreparedValues();

        return $this->renderLayout(
            $this->getLayout(),
            compact(
                'input',
                'field',
                'values',
                'options'
            )
        );
    }

    protected function getPreparedValues()
    {
        $value = $this->getValue();

        if (is_json($value)) {
            $value = json_decode($value, true);
        }

        if ($value['locations'] ?? []) {
            /** @var NestedSetMapper<Location> $mapper */
            $mapper = $this->orm->mapper(Location::class);

            foreach ($value['locations'] as &$location) {
                $pathLocs = $mapper->getPath($location['id']);
                $pathLocs->shift();
                $self = $pathLocs->pop();
                $location['path'] = $pathLocs->column('title')->values()->dump();
                $location['title'] = $self->getTitle();
            }

            unset($location);
        }

        return $value;
    }

    public function prepareStore(mixed $value): mixed
    {
        if (is_json($value)) {
            $value = json_decode($value, true) ?: [];
        }

        return $value;
    }
}

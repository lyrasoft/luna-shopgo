<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Shipping\Basic;

use Brick\Math\BigDecimal;
use Lyrasoft\Luna\Entity\Category;
use Lyrasoft\ShopGo\Cart\CartData;
use Lyrasoft\ShopGo\Entity\Location;
use Lyrasoft\ShopGo\Entity\ProductVariant;
use Lyrasoft\ShopGo\Field\ShippingPricingField;
use Lyrasoft\ShopGo\Shipping\AbstractShipping;
use Lyrasoft\ShopGo\Shipping\PriceRangeTrait;
use Lyrasoft\ShopGo\Shipping\ShippingService;
use Lyrasoft\ShopGo\Traits\CurrencyAwareTrait;
use Unicorn\Field\ButtonRadioField;
use Unicorn\Field\SwitcherField;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Form\Field\NumberField;
use Windwalker\Form\Form;
use Windwalker\ORM\ORM;
use Windwalker\Renderer\CompositeRenderer;

/**
 * The BasicShipping class.
 */
class BasicShipping extends AbstractShipping
{
    use TranslatorTrait;
    use CurrencyAwareTrait;
    use PriceRangeTrait;

    public const COMPUTE_UNIT_PER_ITEM = 'per_item';

    public const COMPUTE_UNIT_PER_ORDER = 'per_order';

    public const DEPENDS_ON_PRICE = 'price';

    public const DEPENDS_ON_WEIGHT = 'weight';

    public static function getTypeIcon(): string
    {
        return 'fa fa-truck';
    }

    public static function getTypeTitle(LangService $lang): string
    {
        return $lang('shopgo.shipping.basic.title');
    }

    public static function getTypeDescription(LangService $lang): string
    {
        return $lang('shopgo.shipping.basic.description');
    }

    public function define(Form $form): void
    {
        $this->registerPricingForm($form);
    }
}

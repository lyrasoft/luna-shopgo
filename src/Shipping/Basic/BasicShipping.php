<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Shipping\Basic;

use Lyrasoft\ShopGo\Cart\Contract\CheckoutProcessLayoutInterface;
use Lyrasoft\ShopGo\Shipping\AbstractShipping;
use Lyrasoft\ShopGo\Shipping\PriceRangeTrait;
use Lyrasoft\ShopGo\Traits\CurrencyAwareTrait;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Core\Renderer\RendererService;
use Windwalker\Form\Form;

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

    public function renderProcessLayout(AppContext $app): mixed
    {
        $rendererService = $app->service(RendererService::class);
        $renderer = $rendererService->createRenderer(
            [
                static::dir() . '/views'
            ]
        );

        return $renderer->render('hello');
    }
}

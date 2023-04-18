<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Script;

use Lyrasoft\Favorite\Script\FavoriteScript;
use Lyrasoft\ShopGo\Service\CurrencyService;
use Unicorn\Script\UnicornScript;
use Windwalker\Core\Asset\AbstractScript;

/**
 * The ShopGoScript class.
 */
class ShopGoScript extends AbstractScript
{
    public function __construct(
        protected CurrencyService $currencyService,
        protected FavoriteScript $favoriteScript,
        protected UnicornScript $unicornScript
    ) {
        //
    }

    public function vueUtilities(): void
    {
        if ($this->available()) {
            $this->currency();

            $this->js('@shopgo/shopgo-vue-utilities.js');
        }
    }

    public function currency(): void
    {
        if ($this->available()) {
            $this->unicornScript->data(
                'currency',
                [
                    'main' => $this->currencyService->getMainCurrency(),
                    'current' => $this->currencyService->getCurrentCurrency()
                ]
            );

            $this->js('@shopgo/currency.js');
        }
    }

    public function productCart(): void
    {
        if ($this->available()) {
            $this->sweetAlert();

            $this->unicornScript->addRoute('@cart_ajax');
            $this->unicornScript->addRoute('@cart');

            $this->js('@shopgo/product-cart.js');
        }
    }

    public function sweetAlert(): void
    {
        if ($this->available()) {
            $this->js('@sweetalert');
        }
    }

    public function wishlistButton(): void
    {
        $this->favoriteScript->favoriteButton();
    }

    public function swiper(?string $selector = null, array $options = []): void
    {
        $defaultOptions = [
            'simulateTouch' => true,
            'allowTouchMove' => true,
            'autoHeight' => true,
        ];

        if ($this->available()) {
            $this->js('vendor/swiper/swiper-bundle.min.js');
            $this->css('vendor/swiper/swiper-bundle.min.css');
        }

        if ($this->available($selector) && $selector) {
            $var = $options['variable_name'] ?? '';

            if ($var) {
                $var = "var $var = ";
            }

            $optionString = static::getJSObject($defaultOptions, $options);
            $this->internalJS(
                $var . "new Swiper('$selector', $optionString);"
            );
        }
    }
}

<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Script;

use Lyrasoft\Favorite\Script\FavoriteScript;
use Lyrasoft\ShopGo\Currency\CurrencyResolver;
use Unicorn\Script\UnicornScript;
use Windwalker\Core\Asset\AbstractScript;

/**
 * The ShopGoScript class.
 */
class ShopGoScript extends AbstractScript
{
    public function __construct(
        protected CurrencyResolver $currencyResolver,
        protected FavoriteScript $favoriteScript,
        protected UnicornScript $unicornScript
    ) {
        //
    }

    public function vueUtilities(): void
    {
        // todo: Remove this
        if ($this->available()) {
            $this->currency();

            // $this->js('@shopgo/shopgo-vue-utilities.js');
        }
    }

    public function currency(): void
    {
        if ($this->available()) {
            $this->unicornScript->data(
                'currency',
                [
                    'main' => $this->currencyResolver->getMainCurrency(),
                    'current' => $this->currencyResolver->getCurrentCurrency()
                ]
            );
        }
    }

    public function currencySwitcher(): void
    {
        if ($this->available()) {
            $this->currency();

            $this->unicornScript->addRoute('@currency_switch');

            $this->unicornScript->importMainThen('u.$shopgo.useCurrencySwitcher();');
        }
    }

    public function productCart(): void
    {
        if ($this->available()) {
            $this->unicornScript->addRoute('@cart_ajax');
            $this->unicornScript->addRoute('@cart');

            $this->unicornScript->importMainThen('u.$shopgo.useProductCartButtons();');
        }
    }

    public function sweetAlert(): void
    {
        // todo: Remove this
        // if ($this->available()) {
        //     $this->js('@sweetalert');
        // }
    }

    public function wishlistButton(): void
    {
        // todo: Remove this
        // $this->favoriteScript->favoriteButton();
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

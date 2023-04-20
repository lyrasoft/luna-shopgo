<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Module\Front\Currency;

use Lyrasoft\ShopGo\Service\CurrencyService;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Attributes\Controller;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\RouteUri;
use Windwalker\Core\Utilities\Base64Url;

/**
 * The CurrencyController class.
 */
#[Controller(
    config: __DIR__ . '/currency.config.php'
)]
class CurrencyController
{
    public function switch(AppContext $app, Navigator $nav, CurrencyService $currencyService): RouteUri
    {
        $code = $app->input('code');
        $return = $app->input('return');

        $state = $app->getState();
        $state->remember('current_currency', $code);

        if ($return) {
            return $nav->to(Base64Url::decode($return));
        }

        return $nav->back();
    }
}

<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

namespace PHPSTORM_META {
    registerArgumentsSet(
        'shopgo_shop_config_args',
    );

    expectedArguments(
        \Lyrasoft\ShopGo\Config\ShopConfig::get(),
        0,
        argumentsSet('shopgo_shop_config_args')
    );

    expectedArguments(
        \Lyrasoft\ShopGo\Config\ShopConfig::getDeep(),
        0,
        argumentsSet('shopgo_shop_config_args')
    );

    // PriceSet
    registerArgumentsSet(
        'price_sets',
        "currency_main",
    );
}

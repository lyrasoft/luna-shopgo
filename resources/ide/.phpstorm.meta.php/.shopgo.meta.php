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
        "currency_main",
        'order_no_prefix',
        'order_no_mode',
        'order_hash_offsets',
        'order_hash_seed',
        'sequence_day_format',
        'invoice_no_prefix',
        'payment_no_maxlength',
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
}

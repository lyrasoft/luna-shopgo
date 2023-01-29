<?php

/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2020 LYRASOFT.
 * @license    MIT
 */

declare(strict_types=1);

use Lyrasoft\Luna\Services\ConfigService;
use Lyrasoft\ShopGo\Config\ShopConfig;
use Windwalker\Data\Collection;
use Windwalker\DI\Container;
use Windwalker\Utilities\Arr;

use function Windwalker\include_arrays;

class_alias(Collection::class, ShopConfig::class);

return Arr::mergeRecursive(
// Load with namespace,
    [
        'factories' => include_arrays(__DIR__ . '/di/*.php'),
        'providers' => [
            \Lyrasoft\Sequence\SequencePackage::class,
        ],
        'bindings' => [
            \App\ShopGoPackage::class,
            \App\Service\CurrencyService::class,
            \App\Service\LocationService::class,
            \App\Service\VariantService::class,
            \App\Service\OrderHistoryService::class,
            \App\Service\CheckoutService::class,
            \App\Service\OrderService::class,
            \App\Service\OrderStateService::class,
            \App\Cart\CartService::class,
            ShopConfig::class => static fn(Container $container) => $container->get(ConfigService::class)
                ->getConfig('shopgo_shop')
        ],
        'aliases' => [
            //
        ],
        'layouts' => [
            //
        ],
        'attributes' => [
            //
        ],
    ]
);

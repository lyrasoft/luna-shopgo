<?php

/**
 * Part of shopgo project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo;

use Lyrasoft\Luna\Services\ConfigService;
use Lyrasoft\ShopGo\Cart\CartService;
use Lyrasoft\ShopGo\Cart\CartStorage;
use Lyrasoft\ShopGo\Config\ShopConfig;
use Lyrasoft\ShopGo\Payment\PaymentService;
use Lyrasoft\ShopGo\Script\ShopGoScript;
use Lyrasoft\ShopGo\Service\AdditionalPurchaseService;
use Lyrasoft\ShopGo\Service\AddressService;
use Lyrasoft\ShopGo\Service\CheckoutService;
use Lyrasoft\ShopGo\Service\CouponService;
use Lyrasoft\ShopGo\Service\CurrencyService;
use Lyrasoft\ShopGo\Service\DiscountService;
use Lyrasoft\ShopGo\Service\DiscountUsageService;
use Lyrasoft\ShopGo\Service\LocationService;
use Lyrasoft\ShopGo\Service\OrderHistoryService;
use Lyrasoft\ShopGo\Service\OrderService;
use Lyrasoft\ShopGo\Service\OrderStateService;
use Lyrasoft\ShopGo\Service\PricingService;
use Lyrasoft\ShopGo\Service\ProductAttributeService;
use Lyrasoft\ShopGo\Service\StockService;
use Lyrasoft\ShopGo\Service\VariantService;
use Lyrasoft\ShopGo\Shipping\ShippingService;
use Windwalker\Core\Application\ApplicationInterface;
use Windwalker\Core\Event\CoreEventAwareTrait;
use Windwalker\Core\Package\AbstractPackage;
use Windwalker\Core\Package\PackageInstaller;
use Windwalker\Data\Collection;
use Windwalker\DI\Container;
use Windwalker\DI\Exception\DefinitionException;
use Windwalker\DI\ServiceProviderInterface;
use Windwalker\Event\EventAwareInterface;
use Windwalker\Utilities\StrNormalize;

/**
 * The ShopGoPackage class.
 */
class ShopGoPackage extends AbstractPackage implements ServiceProviderInterface, EventAwareInterface
{
    use CoreEventAwareTrait;

    public function __construct(protected ApplicationInterface $app)
    {
        //
    }

    /**
     * @param  Container  $container
     *
     * @return  void
     *
     * @throws DefinitionException
     */
    public function register(Container $container): void
    {
        class_alias(Collection::class, ShopConfig::class);

        $container->share(self::class, $this);
        $container->prepareSharedObject(AdditionalPurchaseService::class);
        $container->prepareSharedObject(AddressService::class);
        $container->prepareSharedObject(CartService::class);
        $container->prepareSharedObject(CartStorage::class);
        $container->prepareSharedObject(CouponService::class);
        $container->prepareSharedObject(CheckoutService::class);
        $container->prepareSharedObject(CurrencyService::class);
        $container->prepareSharedObject(DiscountService::class);
        $container->prepareSharedObject(DiscountUsageService::class);
        $container->prepareSharedObject(LocationService::class);
        $container->prepareSharedObject(PaymentService::class);
        $container->prepareSharedObject(PricingService::class);
        $container->prepareSharedObject(OrderHistoryService::class);
        $container->prepareSharedObject(OrderService::class);
        $container->prepareSharedObject(OrderStateService::class);
        $container->prepareSharedObject(ProductAttributeService::class);
        $container->prepareSharedObject(VariantService::class);
        $container->prepareSharedObject(ShippingService::class);
        $container->prepareSharedObject(StockService::class);
        $container->prepareSharedObject(ShopGoScript::class);
        $container->bindShared(
            ShopConfig::class,
            static fn(Container $container) => $container->get(ConfigService::class)
                ->getConfig('shopgo_shop')
        );

        // View
        $container->mergeParameters(
            'renderer.paths',
            [
                static::path('views'),
            ],
            Container::MERGE_OVERRIDE
        );

        // View
        $container->mergeParameters(
            'renderer.edge.components',
            [
                'product-card' => 'front.product.product-card',
            ],
        );

        // Assets
        $container->mergeParameters(
            'asset.import_map.imports',
            [
                '@shopgo/' => 'vendor/lyrasoft/shopgo/dist/',
                '@sweetalert' => 'vendor/sweetalert/dist/sweetalert.min.js',
            ]
        );
    }

    public function install(PackageInstaller $installer): void
    {
        $installer->installConfig(static::path('etc/*.php'), 'config');
        $installer->installLanguages(static::path('resources/languages/**/*.ini'), 'lang');
        $installer->installMigrations(static::path('resources/migrations/**/*'), 'migrations');
        $installer->installSeeders(static::path('resources/seeders/**/*'), 'seeders');
        $installer->installRoutes(static::path('routes/**/*.php'), 'routes');
        $installer->installViews(static::path('views/*.blade.php'), 'views');

        $this->installModules($installer, 'address', ['front', 'model']);
        $this->installModules($installer, 'additional_purchase', ['admin']);
        $this->installModules($installer, 'config', ['admin']);
        $this->installModules($installer, 'cart', ['front']);
        $this->installModules($installer, 'currency', ['admin', 'model']);
        $this->installModules($installer, 'discount', ['admin', 'model']);
        $this->installModules($installer, 'location', ['admin', 'model']);
        $this->installModules($installer, 'manufacturer', ['admin', 'model']);
        $this->installModules($installer, 'order', ['admin', 'model']);
        $this->installModules($installer, 'order_state', ['admin', 'model']);
        $this->installModules($installer, 'payment', ['admin', 'model']);
        $this->installModules($installer, 'product');
        $this->installModules($installer, 'product_attribute', ['admin', 'model']);
        $this->installModules($installer, 'product_attribute_group', ['admin', 'model']);
        $this->installModules($installer, 'product_feature', ['admin', 'model']);
        $this->installModules($installer, 'product_tab', ['admin', 'model']);
        $this->installModules($installer, 'shipping', ['admin', 'model']);

        $installer->installModules(
            [
                static::path("src/Entity/AdditionalPurchase.php") => '@source/Entity',
                static::path("src/Entity/AdditionalPurchaseMap.php") => '@source/Entity',
                static::path("src/Repository/AdditionalPurchaseRepository.php") => '@source/Repository',
            ],
            [
                'Lyrasoft\\ShopGo\\Entity' => 'App\\Entity',
                'Lyrasoft\\ShopGo\\Repository' => 'App\\Repository',
            ],
            ['modules', 'additional_purchase_model']
        );
    }

    protected function installModules(
        PackageInstaller $installer,
        string $name,
        array $modules = ['front', 'admin', 'model']
    ): void {
        $pascal = StrNormalize::toPascalCase($name);

        if (in_array('admin', $modules, true)) {
            $installer->installModules(
                [
                    static::path("src/Module/Admin/$pascal/**/*") => "@source/Module/Admin/$pascal",
                ],
                ['Lyrasoft\\ShopGo\\Module\\Admin' => 'App\\Module\\Admin'],
                ['modules', $name . '_admin'],
            );
        }

        if (in_array('front', $modules, true)) {
            $installer->installModules(
                [
                    static::path("src/Module/Front/$pascal/**/*") => "@source/Module/Front/$pascal",
                ],
                ['Lyrasoft\\ShopGo\\Module\\Front' => 'App\\Module\\Front'],
                ['modules', $name . '_front']
            );
        }

        if (in_array('model', $modules, true)) {
            $installer->installModules(
                [
                    static::path("src/Entity/$pascal.php") => '@source/Entity',
                    static::path("src/Repository/{$pascal}Repository.php") => '@source/Repository',
                ],
                [
                    'Lyrasoft\\ShopGo\\Entity' => 'App\\Entity',
                    'Lyrasoft\\ShopGo\\Repository' => 'App\\Repository',
                ],
                ['modules', $name . '_model']
            );
        }
    }

    public function useFullName(): bool
    {
        return (bool) $this->config('address.use_fullname');
    }

    public function useFullAddress(): bool
    {
        return (bool) $this->config('address.use_fulladdress');
    }

    public function config(string $name, ?string $delimiter = '.'): mixed
    {
        return $this->app->config('shopgo' . $delimiter . $name, $delimiter);
    }
}

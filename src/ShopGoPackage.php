<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo;

use Lyrasoft\Luna\Services\ConfigService;
use Lyrasoft\ShopGo\Cart\CartService;
use Lyrasoft\ShopGo\Cart\CartStorage;
use Lyrasoft\ShopGo\Config\ShopConfig;
use Lyrasoft\ShopGo\Entity\AdditionalPurchase;
use Lyrasoft\ShopGo\Entity\Address;
use Lyrasoft\ShopGo\Entity\Currency;
use Lyrasoft\ShopGo\Entity\Discount;
use Lyrasoft\ShopGo\Entity\Location;
use Lyrasoft\ShopGo\Entity\Manufacturer;
use Lyrasoft\ShopGo\Entity\Order;
use Lyrasoft\ShopGo\Entity\OrderHistory;
use Lyrasoft\ShopGo\Entity\OrderState;
use Lyrasoft\ShopGo\Entity\Payment;
use Lyrasoft\ShopGo\Entity\Product;
use Lyrasoft\ShopGo\Entity\ProductAttribute;
use Lyrasoft\ShopGo\Entity\ProductFeature;
use Lyrasoft\ShopGo\Entity\ProductTab;
use Lyrasoft\ShopGo\Entity\ProductVariant;
use Lyrasoft\ShopGo\Entity\Shipping;
use Lyrasoft\ShopGo\Payment\PaymentService;
use Lyrasoft\ShopGo\Script\ShopGoScript;
use Lyrasoft\ShopGo\Service\AdditionalPurchaseService;
use Lyrasoft\ShopGo\Service\AddressService;
use Lyrasoft\ShopGo\Service\CheckoutService;
use Lyrasoft\ShopGo\Service\CouponService;
use Lyrasoft\ShopGo\Currency\CurrencyResolver;
use Lyrasoft\ShopGo\Service\DiscountService;
use Lyrasoft\ShopGo\Service\DiscountUsageService;
use Lyrasoft\ShopGo\Service\InvoiceService;
use Lyrasoft\ShopGo\Service\LocationService;
use Lyrasoft\ShopGo\Service\MailNotifyService;
use Lyrasoft\ShopGo\Service\OrderHistoryService;
use Lyrasoft\ShopGo\Service\OrderService;
use Lyrasoft\ShopGo\Service\OrderStateService;
use Lyrasoft\ShopGo\Service\PricingService;
use Lyrasoft\ShopGo\Service\ProductAttributeService;
use Lyrasoft\ShopGo\Service\ShipmentService;
use Lyrasoft\ShopGo\Service\StockService;
use Lyrasoft\ShopGo\Service\VariantService;
use Lyrasoft\ShopGo\Shipping\ShippingService;
use Windwalker\Core\Application\ApplicationInterface;
use Windwalker\Core\DI\RequestBootableProviderInterface;
use Windwalker\Core\Event\CoreEventAwareTrait;
use Windwalker\Core\Package\AbstractPackage;
use Windwalker\Core\Package\PackageInstaller;
use Windwalker\Data\Collection;
use Windwalker\DI\Container;
use Windwalker\DI\Exception\DefinitionException;
use Windwalker\DI\MergeOptions;
use Windwalker\DI\ServiceProviderInterface;
use Windwalker\Event\EventAwareInterface;
use Windwalker\Utilities\StrNormalize;

/**
 * The ShopGoPackage class.
 */
class ShopGoPackage extends AbstractPackage implements
    ServiceProviderInterface,
    EventAwareInterface
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

        $container->prepareSharedObject(static::class);
        $container->prepareSharedObject(AdditionalPurchaseService::class);
        $container->prepareSharedObject(AddressService::class);
        $container->prepareSharedObject(CartService::class);
        $container->prepareSharedObject(CartStorage::class);
        $container->prepareSharedObject(CheckoutService::class);
        $container->prepareSharedObject(CouponService::class);
        $container->prepareSharedObject(CurrencyResolver::class);
        $container->prepareSharedObject(DiscountService::class);
        $container->prepareSharedObject(DiscountUsageService::class);
        $container->prepareSharedObject(InvoiceService::class);
        $container->prepareSharedObject(LocationService::class);
        $container->prepareSharedObject(MailNotifyService::class);
        $container->prepareSharedObject(OrderHistoryService::class);
        $container->prepareSharedObject(OrderService::class);
        $container->prepareSharedObject(OrderStateService::class);
        $container->prepareSharedObject(PaymentService::class);
        $container->prepareSharedObject(PricingService::class);
        $container->prepareSharedObject(ProductAttributeService::class);
        $container->prepareSharedObject(ShipmentService::class);
        $container->prepareSharedObject(ShippingService::class);
        $container->prepareSharedObject(ShopGoScript::class);
        $container->prepareSharedObject(StockService::class);
        $container->prepareSharedObject(VariantService::class);
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
            new MergeOptions(override: true)
        );

        // View
        // $container->mergeParameters(
        //     'renderer.edge.components',
        //     [
        //         'product-card' => 'front.components.product-card',
        //         'currency-dropdown' => 'front.components.currency-dropdown',
        //     ],
        // );
        $container->mergeParameters(
            'renderer.edge.component_scans',
            [
                'Lyrasoft\\ShopGo\\Component'
            ],
        );

        // Assets
        $container->mergeParameters(
            'asset.import_map.imports',
            [
                '@shopgo/' => 'vendor/lyrasoft/shopgo/dist/',
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

        $installer->installMVCModules(AdditionalPurchase::class, ['Admin']);
        $installer->installMVCModules(Address::class, ['Front']);
        $installer->installMVCModules('Cart', ['Front'], false);
        $installer->installMVCModules('Checkout', ['Front'], false);
        $installer->installMVCModules('Config', ['Admin'], false);
        $installer->installMVCModules(Currency::class);
        $installer->installMVCModules(Discount::class, ['Admin'], true);
        $installer->installMVCModules('Invoice', ['Admin'], false);
        $installer->installMVCModules(Location::class, ['Admin'], true);
        $installer->installMVCModules(Manufacturer::class, ['Admin'], true);
        $installer->installMVCModules(Order::class);
        $installer->installMVCModules(OrderState::class, ['Admin'], true);
        $installer->installMVCModules(OrderHistory::class, [], true);
        $installer->installMVCModules(Payment::class, ['Admin'], true);
        $installer->installMVCModules(Product::class);
        $installer->installMVCModules(ProductVariant::class, [], true);
        $installer->installMVCModules(ProductAttribute::class, ['Admin'], true);
        $installer->installMVCModules('ProductAttributeGroup', ['Admin'], true);
        $installer->installMVCModules(ProductFeature::class, ['Admin'], true);
        $installer->installMVCModules(ProductTab::class, ['Admin'], true);
        $installer->installMVCModules(Shipping::class, ['Admin'], true);
        $installer->installMVCModules('Wishlist', ['Front'], false);

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

        $folders = [
            'directives',
            'field',
            'services',
            'types',
            'utilities',
        ];

        foreach ($folders as $folder) {
            $installer->installFiles(
                static::path("assets/src/$folder/**/*"),
                "resources/assets/src/shopgo/$folder",
                ['vue', 'vue_base']
            );
        }

        $installer->installFiles(
            static::path('assets/src/shopgo-plugin.ts'),
            'resources/assets/src/shopgo/',
            ['vue', 'vue_base']
        );

        $installer->installFiles(
            static::path('assets/src/modules/additional-purchase/**/*'),
            'resources/assets/src/shopgo/modules/additional-purchase',
            ['vue', 'vue_additional_purchase']
        );

        $installer->installFiles(
            static::path('assets/src/modules/cart/**/*'),
            'resources/assets/src/shopgo/modules/cart',
            ['vue', 'vue_cart']
        );

        $installer->installFiles(
            static::path('assets/src/modules/product-attribute/**/*'),
            'resources/assets/src/shopgo/modules/product-attribute',
            ['vue', 'vue_product_attribute']
        );

        $installer->installFiles(
            static::path('assets/src/modules/product-edit/**/*'),
            'resources/assets/src/shopgo/modules/product-edit',
            ['vue', 'vue_product_edit']
        );

        $installer->installFiles(
            static::path('assets/src/modules/product-feature/**/*'),
            'resources/assets/src/shopgo/modules/product-feature',
            ['vue', 'vue_product_feature']
        );
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

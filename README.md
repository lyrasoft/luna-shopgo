# LYRASOFT ShopGo Package

## Installation

Install from composer

```shell
composer require lyrasoft/shopgo
```

ShopGo dependents on [lyrasoft/sequence](https://github.com/lyrasoft/luna-sequence) and
[lyrasoft/favorite](https://github.com/lyrasoft/luna-favorite) packages. Please read their README and configure them first.

Then copy files to project

```shell
php windwalker pkg:install lyrasoft/shopgo -t routes -t migrations -t seeders
php windwalker pkg:install lyrasoft/favorite -t routes -t migrations
```

### Seeders

Add these files to `resources/seeders/main.php`

```php
return [
    // ...
    
    __DIR__ . '/payment-seeder.php',
    __DIR__ . '/shipping-seeder.php',
    __DIR__ . '/manufacturer-seeder.php',
    __DIR__ . '/product-feature-seeder.php',
    __DIR__ . '/product-attribute-seeder.php',
    __DIR__ . '/product-tab-seeder.php',
    __DIR__ . '/product-seeder.php',
    __DIR__ . '/discount-seeder.php',
    __DIR__ . '/address-seeder.php',
    __DIR__ . '/additional-purchase-seeder.php',
    __DIR__ . '/order-seeder.php',
];
```

Add these types to `category-seeder.php`

```php
    static function () use ($seeder, $orm, $db) {
        $types = [
            // ...
            
            'product' => [
                'max_level' => 2,
                'number' => 30,
            ],
            'attribute' => [
                'max_level' => 1,
                'number' => 10,
            ],
        ];
```

### Global Settings

Open `/etc/packages/shopgo.php`, you can configure there settings:

```php
<?php
// ...

return [
    'shopgo' => [
        // ...

        'currency' => [
            'main' => 'USD' // Can be ID or code
        ],

        'fixtures' => [
            // The migration/seeder faker locale
            'locale' => 'en_US',
        ],

        'address' => [
            // Use fullname or firstname/lastname
            'use_fullname' => false,
            'use_fulladdress' => false,
        ],

        'order_no' => [
            // Order No mode, cab be:
            // INCREMENT_ID: S0000000123
            // DAILY_SEQUENCE: S20230105000123
            // SEQUENCE_HASHES: SfY5Sv8fhJ
            // RANDOM_HASHES: Skf8q2FgHJ38kl (longer)
            'mode' => OrderNoMode::INCREMENT_ID(),
            'prefix' => 'S',
            'hash_offsets' => 100000, // Add offset to hash seed to make no un-guessable
            'sequence_day_format' => 'Ymd',
            // Base62
            // If you want to update this, run:
            // `php -r "echo str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');"`
            'hash_seed' => 'E7G5FHBK9NTifV8tZban2ASvQLeRyYwMWqdhDXs61OuPg0Iploc3kUj4rCJmxz'
        ],

        'payment_no' => [
            // This is the max length of payment No.
            // For intance, Ecpay's limit is 20
            'maxlength' => 20,
        ],

        'invoice_no' => [
            'prefix' => 'INV'
        ],
    ]
];

```

After you configure the base settings, you should not change it after site release.
And then you can run migtaiotns/seeders, all orders No and faker locale will use this setting.

```shell
php windwalker mig:reset -fs
```

### Session

As ShopGo may need to redirect to outside Payment service to process checkout, you must disable `SameSite` cookie poilicy
and set `secure` as `TRUE`.

```php
// etc/packages/session.php

return [
    'session' => [
        // ...

        'cookie_params' => [
            // ...
            'secure' => true, // <-- Set this to TRUE
            // ...
            'samesite' => CookiesInterface::SAMESITE_NONE, // Set this to `SAMESITE_NONE`
        ],
```

### Favorites Type

ShopGo will auto install `lyrasoft/favorite` and copy config file. You must add `product` to `allow_types` to allow
AJAX call.

```php
return [
    'favorite' => [
        // ...

        'ajax' => [
            'type_protect' => true,
            'allow_types' => [
                'article',
                'product' // <-- Add this
            ]
        ],
    ]
];
```

### Language Files

Add this line to admin & front middleware if you don't want to override languages:

```php
$this->lang->loadAllFromVendor('lyrasoft/shopgo', 'ini');
$this->lang->loadAllFromVendor('lyrasoft/favorite', 'ini');

```

Or run this command to copy languages files:

```shell
php windwalker pkg:install lyrasoft/shopgo -t lang
php windwalker pkg:install lyrasoft/favorite -t lang
```

### CSS/JS

ShopGo dependents on `lyrasoft/favorite`, you must add these vendors to `fusionfile.mjs`

```javascript
export async function install() {
    return installVendors(
        [
            // ...

            // Add these below
            'sweetalert',
            'swiper',
        ],
        [
            // Add these 2 lines
            'lyrasoft/shopgo',
            'lyrasoft/favorite',
        ]
    );
}
```

Then run this command to install npm vendors:

```shell
yarn add swiper sweetalert
```

The ShopGo Scripts will auto-register in destinestion pages. But if you want, you can register it globally in `FrontMiddleware`.

```php
class FrontMiddleware extends AbstractLifecycleMiddleware
{
    // ...

    public function __construct(
        // ...
        protected ShopGoScript $shopGoScript,
    ) {
    }

    protected function preprocess(ServerRequestInterface $request): void
    {
        // ...

        $this->shopGoScript->sweetAlert();
        $this->shopGoScript->productCart();
```

If you want ot make all JS alert as SweetAlert style, you can replace `u.alert` at `main.js`

```javascript
// main.js

// ...

u.alert = swal;
```


### Add Cart Button

Currently ShopGo Beta has no cart button widget. You must add it to HTML manually.

You must includes these 2 attributes to make JS works:

- `[data-role=cart-button]`
- `[data-role=cart-quantity]`

```php
<?php
$cartStorage = $app->service(\Lyrasoft\ShopGo\Cart\CartStorage::class);
$cartQuantity = $cartStorage->count();
?>
<div class="c-cart-button"
    data-role="cart-button">
    <div class="c-cart-button__quantity">
        <i class="fa fa-cart-shopping"></i>
        
        <span class="badge bg-danger"
            data-role="cart-quantity">
            {{ $cartQuantity }}
        </span>
    </div>
</div>
```

## Register Admin Menu

Edit `resources/menu/admin/sidemenu.menu.php`

```php
$menu->link('商城管理', '#')
    ->icon('fal fa-shop');

$menu->registerChildren(
    function (MenuBuilder $menu) use ($nav, $lang) {
        $menu->link($lang('shopgo.product.category.title'))
            ->to($nav->to('category_list', ['type' => 'product']))
            ->icon('fal fa-sitemap');

        $menu->link($lang('unicorn.title.grid', title: $lang('shopgo.product.title')))
            ->to($nav->to('product_list'))
            ->icon('fal fa-box-open');

        $menu->link($lang('unicorn.title.grid', title: $lang('shopgo.additional.purchase.title')))
            ->to($nav->to('additional_purchase_list'))
            ->icon('fal fa-cart-plus');

        $menu->link($lang('unicorn.title.grid', title: $lang('shopgo.product.feature.title')))
            ->to($nav->to('product_feature_list'))
            ->icon('fal fa-object-ungroup');

        $menu->link($lang('unicorn.title.grid', title: $lang('shopgo.product.attribute.group.title')))
            ->to($nav->to('product_attribute_group_list'))
            ->icon('fal fa-object-group');

        $menu->link($lang('unicorn.title.grid', title: $lang('shopgo.product.attribute.title')))
            ->to($nav->to('product_attribute_list'))
            ->icon('fal fa-rectangle-list');

        $menu->link($lang('unicorn.title.grid', title: $lang('shopgo.product.tab.title')))
            ->to($nav->to('product_tab_list'))
            ->icon('fal fa-pager');

        $menu->link($lang('unicorn.title.grid', title: $lang('shopgo.manufacturer.title')))
            ->to($nav->to('manufacturer_list'))
            ->icon('fal fa-building');
    }
);

$menu->link('優惠', '#')
    ->icon('fal fa-cart-arrow-down');

$menu->registerChildren(
    function (MenuBuilder $menu) use ($nav, $lang) {
        $menu->link($lang('unicorn.title.grid', title: $lang('shopgo.discount.title')))
            ->to($nav->to('discount_list'))
            ->icon('fal fa-percent');
    }
);

$menu->link('訂單', '#')
    ->icon('fal fa-file-invoice-dollar');

$menu->registerChildren(
    function (MenuBuilder $menu) use ($nav, $lang) {
        $menu->link($lang('unicorn.title.grid', title: $lang('shopgo.order.title')))
            ->to($nav->to('order_list'))
            ->icon('fal fa-file-invoice-dollar');

        $menu->link($lang('unicorn.title.grid', title: $lang('luna.order_state.title')))
            ->to($nav->to('order_state_list'))
            ->icon('fal fa-list');
    }
);

$menu->link('商城設定', '#')
    ->icon('fal fa-cogs');

$menu->registerChildren(
    function (MenuBuilder $menu) use ($nav, $lang) {
        $menu->link($lang('unicorn.title.grid', title: $lang('shopgo.currency.title')))
            ->to($nav->to('currency_list'))
            ->icon('fal fa-sterling-sign');

        $menu->link($lang('unicorn.title.grid', title: $lang('luna.location.title')))
            ->to($nav->to('location_list'))
            ->icon('fa-solid fa-marker')
            ->icon('fal fa-earth-asia');

        $menu->link($lang('unicorn.title.grid', title: $lang('luna.payment.title')))
            ->to($nav->to('payment_list'))
            ->icon('fa-solid fa-dollar')
            ->icon('fal fa-cash-register');

        $menu->link($lang('unicorn.title.grid', title: $lang('luna.shipping.title')))
            ->to($nav->to('shipping_list'))
            ->icon('fal fa-truck');

        $menu->link($lang('luna.config.title', $lang('shopgo.config.type.shop')))
            ->to($nav->to('config_shopgo_shop'))
            ->icon('fal fa-gear');
    }
);

```


# LYRASOFT ShopGo Package

## Installation

Install from composer

```shell
composer require lyrasoft/shopgo
```

You must register `SequencePackage` to `stc/di.php` first:

```php
        'providers' => [
            \Lyrasoft\Sequence\SequencePackage::class,
        ],
```

Then copy files to project

```shell
php windwalker pkg:install lyrasoft/shopgo -t routes -t migrations -t seeders
```

Seeders

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
            'attribute_group' => [
                'max_level' => 1,
                'number' => 10,
            ],
        ];
```

Languages

Add this line to admin & front middleware if you don't want to override languages:

```php
$this->lang->loadAllFromVendor('lyrasoft/shopgo', 'ini');
```

Or run this command to copy languages files:

```shell
php windwalker pkg:install lyrasoft/shopgo -t lang
```

## Register Admin Menu

Edit `resources/menu/admin/sidemenu.menu.php`

```php
// Category
$menu->link('作品分類')
    ->to($nav->to('category_list', ['type' => 'portfolio']))
    ->icon('fal fa-sitemap');

// Portfolio
$menu->link('作品管理')
    ->to($nav->to('portfolio_list'))
    ->icon('fal fa-images');
```


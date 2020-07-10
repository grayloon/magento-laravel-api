
<p align="center">
  <img src="logo.png">
</p>

<p align="center">
<a href="https://github.com/grayloon/magento-laravel-api/actions"><img src="https://github.com/grayloon/magento-laravel-api/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/grayloon/magento-laravel-api"><img src="https://img.shields.io/packagist/v/grayloon/magento-laravel-api.svg?style=flat" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/grayloon/magento-laravel-api"><img src="https://github.styleci.io/repos/277585119/shield?branch=master" alt="Style CI"></a>
<a href="https://packagist.org/packages/grayloon/magento-laravel-api"><img src="https://img.shields.io/packagist/dt/grayloon/magento-laravel-api.svg?style=flat" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/grayloon/magento-laravel-api"><img src="https://img.shields.io/badge/License-MIT-brightgreen.svg" alt="License"></a>
</p>

# Laravel - Magento API

A Magento 2 API Object Oriented wrapper for a Laravel application. Also includes an optional opinionated storage system for 
consuming Magento 2 data in your Laravel application.

## Installation

You can install the package via composer:

```bash q
composer require grayloon/laravel-magento-api
```

Publish the config options:
```bash
php artisan vendor:publish --provider="Grayloon\Magento\MagentoServiceProvider" --tag="config"
```

Optional:
If you are wanting to store the data from the Magento API and want to use our opinionated storage system, publish the migrations:
```bash
php artisan vendor:publish --provider="Grayloon\Magento\MagentoServiceProvider" --tag="migrations"
```

Configure your Magento 2 API endpoint and token in your `.env` file:
```
MAGENTO_BASE_URL="https://mydomain.com"
MAGENTO_ACCESS_TOKEN="client_access_token_here"
```

## API Usage

Example:
```php
use Grayloon\Magento\Magento;

$products = Magento::api('products')->all(); // array
```

### Available Methods:

#### Categories

Get a list of all categories:
```php
Magento::api('categories')-->all($pageSize = 50, $currentPage = 1);
```

Get a count of all categories:
```php
Magento::api('categories')->count(); 
```

#### Products
Get a list of products:
```php
Magento::api('products')->all($pageSize = 50, $currentPage = 1); 
```

Get a count of all products:
```php
Magento::api('products')->count(); 
```

Get info about a product by the product SKU:
```php
Magento::api('products')->show($sku);
```

#### Schema
Get a schema blueprint of the Magento 2 REST API:
```php
Magento::api('schema')->show(); 
```

## Jobs

> In order use these queue jobs, you must have registered the migrations from the installation section noted above.

This package has many pre-built queue jobs to sync your Magento products to your Laravel application. Feel free to leverage these jobs or create your own.

Updates all products from the Magento API:
```php
Bus::dispatch(\Grayloon\Magento\Jobs\SyncMagentoProducts::class);
```

Updates a specified product from the Magento API:
```php
Bus::dispatch(\Grayloon\Magento\Jobs\SyncMagentoProduct::class, $sku);
```

Updates all categories from the Magento API:
```php
Bus::dispatch(\Grayloon\Magento\Jobs\SyncMagentoCategories::class);
```


## API / Webhooks

> In order use these api routes, you must have registered the migrations from the installation section noted above.

This package has included routes to automatically update Magento information from the API. These can be utilized with Magento Webhooks to keep your items in sync.

> All routes are guarded by the default `API` Laravel middleware.

Fire the `SyncMagentoProduct($sku)` job to update a specified product SKU:
```
/api/laravel-magento-api/products/update/{sku}
```


## Commands

> In order use these commands, you must have registered the migrations from the installation section noted above.

Launch a job to import all products from the Magento 2 REST API:
```bash
php artisan magento:sync-products
```

Launch a job to import all categories from the Magento 2 REST API:
```bash
php artisan magento:sync-categories
```

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email webmaster@grayloon.com instead of using the issue tracker.

## Credits

- [Gray Loon Marketing Group](https://github.com/grayloon)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
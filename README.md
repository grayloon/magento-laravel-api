# Magento - Laravel API

Magento 2 REST API wrapper to export Magento data for consumption in your Laravel application. This package is a work in progress - do not attempt to use in production.

[![Build Status](https://github.com/grayloon/magento-laravel-api/workflows/tests/badge.svg)](https://github.com/grayloon/magento-laravel-api/actions)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/grayloon/magento-laravel-api.svg?style=flat-square)](https://packagist.org/packages/grayloon/magento-laravel-api)
[![Total Downloads](https://img.shields.io/packagist/dt/grayloon/magento-laravel-api.svg?style=flat-square)](https://packagist.org/packages/grayloon/magento-laravel-api)

## Installation

You can install the package via composer:

```bash
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

#### Products
Get a list of products:
```php
Magento::api('products')->all($pageSize = 50, $currentPage = 1); 
```

#### Schema
Get a schema blueprint of the Magento 2 REST Api:
```php
Magento::api('schema')->show(); 
```

## Commands

> In order use these commands, you must have registered the migrations from the installation section noted above.

You can easily import your Magento 2 data into your application by utilizing these commands:

Import all products from the Magento 2 REST API:
```bash
php artisan magento:sync-products
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
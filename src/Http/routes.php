<?php

use Grayloon\Magento\Http\Controllers\MagentoProductController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api', 'middleware' => 'api'], function () {
    Route::group(['prefix' => 'laravel-magento-api'], function () {
        Route::get('products/update/{sku}', [MagentoProductController::class, 'update'])
            ->name('laravel-magento-api.products.update');
    });
});

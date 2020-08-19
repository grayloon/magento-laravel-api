<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Grayloon\Magento\Models\MagentoProduct;
use Grayloon\Magento\Models\MagentoStockItem;

$factory->define(MagentoStockItem::class, function (Faker $faker) {
    return [
        'item_id'     => 1,
        'product_id'  => factory(MagentoProduct::class)->create(),
        'stock_id'    => 1,
        'qty'         => rand(1, 10000),
        'is_in_stock' => true,
    ];
});

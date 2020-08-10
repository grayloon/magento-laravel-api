<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Grayloon\Magento\Models\MagentoProduct;

$factory->define(MagentoProduct::class, function (Faker $faker) {
    return [
        'id'         => rand(1, 10000),
        'sku'        => $faker->ean8,
        'name'       => $faker->bs,
        'price'      => $faker->randomFloat(2, 1, 500),
        'status'     => 1,
        'visibility' => 1,
        'type'       => 'simple',
        'slug'       => $faker->slug,
    ];
});

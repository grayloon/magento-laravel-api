<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Grayloon\Magento\Models\MagentoProduct;
use Grayloon\Magento\Models\MagentoProductMedia;

$factory->define(MagentoProductMedia::class, function (Faker $faker) {
    return [
        'id'          => rand(1, 10000),
        'product_id'  => factory(MagentoProduct::class)->create(),
        'media_type'  => 'image',
        'position'    => 1,
        'disabled'    => false,
        'file'        => '/f/foo.jpg',
    ];
});

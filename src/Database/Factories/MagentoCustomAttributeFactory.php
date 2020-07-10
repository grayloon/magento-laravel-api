<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Grayloon\Magento\Models\MagentoProduct;
use Grayloon\Magento\Models\MagentoCustomAttribute;

$factory->define(MagentoCustomAttribute::class, function (Faker $faker) {
    return [
        'attribute_type'      => $faker->bs,
        'value'               => $faker->catchPhrase,
        'attributable_type'   => $faker->randomElement([MagentoProduct::class]),
        'attributable_id'     => fn (array $attribute) => factory($attribute['attributable_type']),
    ];
});

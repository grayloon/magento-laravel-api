<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Grayloon\Magento\Models\MagentoCategory;
use Grayloon\Magento\Models\MagentoCustomAttribute;
use Grayloon\Magento\Models\MagentoProduct;

$factory->define(MagentoCustomAttribute::class, function (Faker $faker) {
    return [
        'attribute_type'      => $faker->bs,
        'value'               => $faker->catchPhrase,
        'attributable_type'   => $faker->randomElement([MagentoProduct::class, MagentoCategory::class]),
        'attributable_id'     => fn (array $attribute) => factory($attribute['attributable_type']),
    ];
});

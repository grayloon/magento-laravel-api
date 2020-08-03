<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Grayloon\Magento\Models\MagentoCategory;

$factory->define(MagentoCategory::class, function (Faker $faker) {
    return [
        'name'            => $faker->catchPhrase,
        'is_active'       => true,
        'position'        => random_int(1, 100),
        'level'           => random_int(1, 100),
        'path'            => '1/1',
        'include_in_menu' => true,
        'slug'            => $faker->slug,
    ];
});

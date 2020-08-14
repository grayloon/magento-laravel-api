<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Grayloon\Magento\Models\MagentoProductLinkType;

$factory->define(MagentoProductLinkType::class, function (Faker $faker) {
    return [
        'name' => $faker->catchPhrase,
    ];
});

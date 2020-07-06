<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Grayloon\Magento\Models\MagentoExtAttributeType;

$factory->define(MagentoExtAttributeType::class, function (Faker $faker) {
    return [
        'type' => $faker->bs,
    ];
});

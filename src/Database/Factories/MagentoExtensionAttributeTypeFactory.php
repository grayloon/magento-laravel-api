<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Grayloon\Magento\Models\MagentoExtensionAttributeType;

$factory->define(MagentoExtensionAttributeType::class, function (Faker $faker) {
    return [
        'type' => $faker->bs,
    ];
});

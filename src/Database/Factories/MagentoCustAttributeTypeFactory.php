<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Grayloon\Magento\Models\MagentoCustAttributeType;
use Faker\Generator as Faker;

$factory->define(MagentoCustAttributeType::class, function (Faker $faker) {
    return [
        'type' => $faker->bs,
    ];
});

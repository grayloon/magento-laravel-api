<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Grayloon\Magento\Models\MagentoCustAttributeType;

$factory->define(MagentoCustAttributeType::class, function (Faker $faker) {
    return [
        'type' => $faker->bs,
    ];
});

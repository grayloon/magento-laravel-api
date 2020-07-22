<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Grayloon\Magento\Models\MagentoCustomer;

$factory->define(MagentoCustomer::class, function (Faker $faker) {
    return [
        'id'                 => rand(1, 10000),
        'group_id'           => rand(1, 10000),
        'email'              => $faker->safeEmail,
        'first_name'         => $faker->firstName,
        'last_name'          => $faker->lastName,
        'store_id'           => 1,
        'website_id'         => 1,
    ];
});

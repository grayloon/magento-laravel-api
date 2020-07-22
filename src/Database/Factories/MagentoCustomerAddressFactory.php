<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Grayloon\Magento\Models\MagentoCustomer;
use Grayloon\Magento\Models\MagentoCustomerAddress;

$factory->define(MagentoCustomerAddress::class, function (Faker $faker) {
    return [
        'id'           => rand(1, 10000),
        'customer_id'  => factory(MagentoCustomer::class)->create(),
        'region_code'  => $faker->stateAbbr,
        'region'       => $faker->state,
        'region_id'    => rand(1, 10000),
        'street'       => $faker->streetAddress,
        'telephone'    => $faker->phoneNumber,
        'postal_code'  => $faker->postcode,
        'city'         => $faker->city,
        'first_name'   => $faker->firstName,
        'last_name'    => $faker->lastName,
    ];
});

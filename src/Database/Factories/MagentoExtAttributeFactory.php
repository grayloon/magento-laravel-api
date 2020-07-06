<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Grayloon\Magento\Models\MagentoExtAttribute;
use Grayloon\Magento\Models\MagentoExtAttributeType;
use Grayloon\Magento\Models\MagentoProduct;

$factory->define(MagentoExtAttribute::class, function (Faker $faker) {
    return [
        'magento_product_id'            => factory(MagentoProduct::class)->create(),
        'magento_ext_attribute_type_id' => factory(MagentoExtAttributeType::class)->create(),
        'attribute'                     => [$faker->catchPhrase],
    ];
});

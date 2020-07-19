<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Grayloon\Magento\Models\MagentoExtensionAttribute;
use Grayloon\Magento\Models\MagentoExtensionAttributeType;
use Grayloon\Magento\Models\MagentoProduct;

$factory->define(MagentoExtensionAttribute::class, function (Faker $faker) {
    return [
        'magento_product_id'            => factory(MagentoProduct::class)->create(),
        'magento_ext_attribute_type_id' => factory(MagentoExtensionAttributeType::class)->create(),
        'attribute'                     => [$faker->catchPhrase],
    ];
});

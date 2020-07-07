<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Grayloon\Magento\Models\MagentoProduct;
use Grayloon\Magento\Models\MagentoCustAttribute;
use Grayloon\Magento\Models\MagentoCustAttributeType;

$factory->define(MagentoCustAttribute::class, function (Faker $faker) {
    return [
        'magento_product_id'             => factory(MagentoProduct::class)->create(),
        'magento_cust_attribute_type_id' => factory(MagentoCustAttributeType::class)->create(),
        'attribute'                      => 'foo',
    ];
});

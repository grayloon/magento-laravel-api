<?php

namespace Grayloon\Magento\Tests;

use Grayloon\Magento\Models\MagentoProduct;
use Grayloon\Magento\Models\MagentoCategory;
use Grayloon\Magento\Models\MagentoCustomAttribute;

class MagentoCustomAttributeTypeModelTest extends TestCase
{
    public function test_can_create_magento_custom_attribute()
    {
        $attribute = factory(MagentoCustomAttribute::class)->create();

        $this->assertNotEmpty($attribute);
    }

    public function test_can_create_magento_custom_attribute_poly_type_can_be_product()
    {
        $attribute = factory(MagentoCustomAttribute::class)->create([
            'attributable_type'   => MagentoProduct::class,
            'attributable_id'     => fn (array $attribute) => factory($attribute['attributable_type']),
        ]);

        $this->assertNotEmpty($attribute);
        $this->assertEquals(MagentoProduct::class, $attribute->attributable_type);
    }

    public function test_can_create_magento_custom_attribute_poly_type_can_be_category()
    {
        $attribute = factory(MagentoCustomAttribute::class)->create([
            'attributable_type'   => MagentoCategory::class,
            'attributable_id'     => fn (array $attribute) => factory($attribute['attributable_type']),
        ]);

        $this->assertNotEmpty($attribute);
        $this->assertEquals(MagentoCategory::class, $attribute->attributable_type);
    }
}

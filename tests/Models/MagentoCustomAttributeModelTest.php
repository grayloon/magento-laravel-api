<?php

namespace Grayloon\Magento\Tests;

use Grayloon\Magento\Models\MagentoCategory;
use Grayloon\Magento\Models\MagentoCustomAttribute;
use Grayloon\Magento\Models\MagentoCustomAttributeType;
use Grayloon\Magento\Models\MagentoProduct;

class MagentoCustomAttributeModelTest extends TestCase
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

    public function test_custom_attribute_type_id_belongs_to_attribute_type_relationship()
    {
        $type = factory(MagentoCustomAttributeType::class)->create();
        $attribute = factory(MagentoCustomAttribute::class)->create([
            'attribute_type' => $type->id,
        ]);

        $query = MagentoCustomAttribute::with('type')->first();

        $this->assertNotEmpty($attribute);
        $this->assertEquals($attribute->attribute_type, $type->id);
        $this->assertInstanceOf(MagentoCustomAttributeType::class, $query->type()->first());
    }
}

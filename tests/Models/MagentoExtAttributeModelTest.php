<?php

namespace Grayloon\Magento\Tests;

use Grayloon\Magento\Models\MagentoExtAttribute;

class MagentoExtAttributeModelTest extends TestCase
{
    public function test_can_create_magento_ext_attribute()
    {
        $attribute = factory(MagentoExtAttribute::class)->create();

        $this->assertNotEmpty($attribute);
    }

    public function test_magento_ext_attribute_has_ext_attribute_type()
    {
        $attribute = factory(MagentoExtAttribute::class)->create();

        $type = $attribute->type()->first();

        $this->assertNotEmpty($type);
        $this->assertEquals($type->id, $attribute->magento_ext_attribute_type_id);
    }
}

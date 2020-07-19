<?php

namespace Grayloon\Magento\Tests;

use Grayloon\Magento\Models\MagentoExtensionAttribute;

class MagentoExtensionAttributeModelTest extends TestCase
{
    public function test_can_create_magento_ext_attribute()
    {
        $attribute = factory(MagentoExtensionAttribute::class)->create();

        $this->assertNotEmpty($attribute);
    }

    public function test_magento_ext_attribute_has_ext_attribute_type()
    {
        $attribute = factory(MagentoExtensionAttribute::class)->create();

        $type = $attribute->type()->first();

        $this->assertNotEmpty($type);
        $this->assertEquals($type->id, $attribute->magento_ext_attribute_type_id);
    }
}

<?php

namespace Grayloon\Magento\Tests;

use Grayloon\Magento\Models\MagentoCustAttribute;
use Grayloon\Magento\Models\MagentoCustAttributeType;

class MagentoCustAttributeModelTest extends TestCase
{
    public function test_can_create_magento_cust_attribute_type()
    {
        $type = factory(MagentoCustAttributeType::class)->create();

        $this->assertNotEmpty($type);
    }


    public function test_magento_cust_attribute_has_cust_attribute_type()
    {
        $attribute = factory(MagentoCustAttribute::class)->create();

        $type = $attribute->type()->first();

        $this->assertNotEmpty($type);
        $this->assertEquals($type->id, $attribute->magento_cust_attribute_type_id);
    }
}

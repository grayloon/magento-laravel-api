<?php

namespace Grayloon\Magento\Tests;

use Grayloon\Magento\Models\MagentoExtensionAttributeType;

class MagentoExtensionAttributeTypeModelTest extends TestCase
{
    public function test_can_create_magento_ext_attribute_type()
    {
        $type = factory(MagentoExtensionAttributeType::class)->create();

        $this->assertNotEmpty($type);
    }
}

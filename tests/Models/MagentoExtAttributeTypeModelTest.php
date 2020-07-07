<?php

namespace Grayloon\Magento\Tests;

use Grayloon\Magento\Models\MagentoExtAttributeType;

class MagentoExtAttributeTypeModelTest extends TestCase
{
    public function test_can_create_magento_ext_attribute_type()
    {
        $type = factory(MagentoExtAttributeType::class)->create();

        $this->assertNotEmpty($type);
    }
}

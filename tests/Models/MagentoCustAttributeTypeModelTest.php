<?php

namespace Grayloon\Magento\Tests;

use Grayloon\Magento\Models\MagentoCustAttribute;

class MagentoCustAttributeTypeModelTest extends TestCase
{
    public function test_can_create_magento_cust_attribute()
    {
        $attribute = factory(MagentoCustAttribute::class)->create();

        $this->assertNotEmpty($attribute);
    }
}

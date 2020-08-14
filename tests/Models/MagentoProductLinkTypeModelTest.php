<?php

namespace Grayloon\Magento\Tests;

use Grayloon\Magento\Models\MagentoProductLinkType;

class MagentoProductLinkTypeModelTest extends TestCase
{
    public function test_can_create_magento_product_link_type()
    {
        $type = factory(MagentoProductLinkType::class)->create();

        $this->assertNotEmpty($type);
    }

    public function test_product_link_type_is_fillable()
    {
        $type = factory(MagentoProductLinkType::class)->create([
            'name' => 'foo',
            'synced_at' => '2020-01-01 00:00:00',
            'id' => 2,
        ]);

        $this->assertNotEmpty($type);
        $this->assertEquals(2, $type->id);
        $this->assertEquals('foo', $type->name);
        $this->assertEquals('2020-01-01 00:00:00', $type->synced_at);
    }
}

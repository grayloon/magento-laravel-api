<?php

namespace Grayloon\Magento\Tests;

use Grayloon\Magento\Models\MagentoProduct;
use Grayloon\Magento\Models\MagentoProductLink;

class MagentoProductLinksModelTest extends TestCase
{
    public function test_can_create_magento_product_link()
    {
        $link = factory(MagentoProductLink::class)->create();

        $this->assertNotEmpty($link);
    }

    public function test_magento_product_link_product_id_belongs_to_product()
    {
        $product = factory(MagentoProduct::class)->create();
        $link = factory(MagentoProductLink::class)->create([
            'product_id' => $product->id,
        ]);

        $this->assertNotEmpty($link->product_id);
        $this->assertEquals($link->product->id, $link->product_id);
        $this->assertInstanceOf(MagentoProduct::class, $link->product);
        $this->assertEquals($link->product->id, $product->id);
    }

    public function test_magento_product_link_related_product_id_belongs_to_product()
    {
        $product = factory(MagentoProduct::class)->create();
        $related = factory(MagentoProduct::class)->create();
        $link = factory(MagentoProductLink::class)->create([
            'product_id' => $product->id,
            'related_product_id' => $related->id,
        ]);

        $this->assertNotEmpty($link->related_product_id);
        $this->assertEquals($link->related->id, $link->related_product_id);
        $this->assertInstanceOf(MagentoProduct::class, $link->related);
        $this->assertEquals($link->related->id, $related->id);
        $this->assertNotEquals($product->id, $link->related->id);
    }
}

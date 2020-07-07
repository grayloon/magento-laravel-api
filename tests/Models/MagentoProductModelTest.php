<?php

namespace Grayloon\Magento\Tests;

use Grayloon\Magento\Models\MagentoProduct;
use Grayloon\Magento\Models\MagentoExtAttribute;

class MagentoProductModelTest extends TestCase
{
    public function test_can_create_magento_product()
    {
        $product = factory(MagentoProduct::class)->create();

        $this->assertNotEmpty($product);
    }

    public function test_magento_product_has_many_attributes()
    {
        factory(MagentoExtAttribute::class, 2)->create([
            'magento_product_id' => $product = factory(MagentoProduct::class)->create(),
        ]);

        $this->assertNotEmpty($product->ExtAttributes()->get());
        $this->assertCount(2, $product->ExtAttributes()->get());
    }
}

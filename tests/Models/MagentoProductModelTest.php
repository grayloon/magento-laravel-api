<?php

namespace Grayloon\Magento\Tests;

use Grayloon\Magento\Models\MagentoExtAttribute;
use Grayloon\Magento\Tests\TestCase;
use Grayloon\Magento\Models\MagentoProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MagentoProductModelTest extends TestCase
{
    public function test_can_create_magento_product()
    {
        $product = factory(MagentoProduct::class)->create();

        $this->assertNotEmpty($product);
    }

    public function test_magento_product_has_many_attributes()
    {
        factory(MagentoExtAttribute::class, 30)->create([
            'magento_product_id' => $product = factory(MagentoProduct::class)->create(),
        ]);

        $this->assertNotEmpty($product->ExtAttributes()->get());
        $this->assertCount(30, $product->ExtAttributes()->get());
    }
}

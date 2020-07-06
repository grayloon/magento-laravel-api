<?php

namespace Grayloon\Magento\Tests;

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
}

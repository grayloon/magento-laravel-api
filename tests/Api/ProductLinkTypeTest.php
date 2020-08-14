<?php

namespace Grayloon\Magento\Tests;

use Grayloon\Magento\Api\ProductLinkType;
use Grayloon\Magento\Magento;
use Illuminate\Support\Facades\Http;

class ProductLinkTypeTest extends TestCase
{
    public function test_can_call_product_link_type()
    {
        $magento = new Magento();

        $this->assertInstanceOf(ProductLinkType::class, $magento->api('productLinkType'));
    }

    public function test_can_call_product_link_type_types()
    {
        Http::fake();

        $magento = new Magento();
        $api = $magento->api('productLinkType')->types();

        $this->assertTrue($api->ok());
    }
}

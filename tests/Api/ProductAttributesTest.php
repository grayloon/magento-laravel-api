<?php

namespace Grayloon\Magento\Tests;

use Grayloon\Magento\Api\ProductAttributes;
use Grayloon\Magento\Magento;
use Illuminate\Support\Facades\Http;

class ProductAttributesTest extends TestCase
{
    public function test_can_call_magento_api_product_attributes()
    {
        $magento = new Magento();

        $this->assertInstanceOf(ProductAttributes::class, $magento->api('productAttributes'));
    }

    public function test_can_call_magento_api_product_attributes_show()
    {
        Http::fake();

        $magento = new Magento();
        $api = $magento->api('productAttributes')->show('foo');

        $this->assertTrue($api->ok());
    }
}

<?php

namespace Interiordefine\Magento\Tests;

use Illuminate\Support\Facades\Http;
use Interiordefine\Magento\Api\ProductAttributes;
use Interiordefine\Magento\MagentoFacade;

class ProductAttributesTest extends TestCase
{
    public function test_can_call_magento_api_product_attributes()
    {
        $this->assertInstanceOf(ProductAttributes::class, MagentoFacade::api('productAttributes'));
    }

    public function test_can_call_magento_api_product_attributes_show()
    {
        Http::fake();

        $api = MagentoFacade::api('productAttributes')->show('foo');

        $this->assertTrue($api->ok());
    }

    public function test_can_call_magento_api_product_attributes_all()
    {
        Http::fake([
            '*rest/all/V1/products/attributes*' => Http::response([], 200),
        ]);

        $api = MagentoFacade::api('productAttributes')->all();

        $this->assertTrue($api->ok());
    }
}

<?php

namespace Grayloon\Magento\Tests;

use Grayloon\Magento\Api\Products;
use Grayloon\Magento\MagentoFacade;
use Illuminate\Support\Facades\Http;

class ProductTest extends TestCase
{
    public function test_can_call_magento_api_products()
    {
        $this->assertInstanceOf(Products::class, MagentoFacade::api('products'));
    }

    public function test_can_call_magento_api_products_all()
    {
        Http::fake();

        $api = MagentoFacade::api('products')->all();

        $this->assertTrue($api->ok());
    }

    public function test_can_call_magento_api_products_show()
    {
        Http::fake();

        $api = MagentoFacade::api('products')->show('foo');

        $this->assertTrue($api->ok());
    }

    public function test_can_edit_product()
    {
        Http::fake([
            '*rest/all/V1/products/foo' => Http::response([], 200),
        ]);

        $api = MagentoFacade::api('products')->edit('foo', []);

        $this->assertTrue($api->ok());
    }
}

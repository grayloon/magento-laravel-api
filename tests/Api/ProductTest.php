<?php

namespace Interiordefine\Magento\Tests;

use Illuminate\Support\Facades\Http;
use Interiordefine\Magento\Api\Products;
use Interiordefine\Magento\MagentoFacade;

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

        $api = MagentoFacade::api('products')->getBySku('foo');

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

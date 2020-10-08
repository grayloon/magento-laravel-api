<?php

namespace Grayloon\Magento\Tests;

use Grayloon\Magento\Api\Categories;
use Grayloon\Magento\MagentoFacade;
use Illuminate\Support\Facades\Http;

class CategoriesTest extends TestCase
{
    public function test_can_call_magento_api_categories()
    {
        $this->assertInstanceOf(Categories::class, MagentoFacade::api('categories'));
    }

    public function test_can_call_magento_api_products_all()
    {
        Http::fake();

        $api = MagentoFacade::api('categories')->all();

        $this->assertTrue($api->ok());
    }

    public function test_can_call_magento_api_categories_products()
    {
        Http::fake([
            '*rest/default/V1/categories/1/products' => Http::response([], 200),
        ]);

        $api = MagentoFacade::setStoreCode('default')->api('categories')->products(1);

        $this->assertTrue($api->ok());
    }

    public function test_magento_api_categories_products_requires_store_code()
    {
        $this->expectException('exception');

        $api = MagentoFacade::api('categories')->products(1);
    }
}

<?php

namespace Interiordefine\Magento\Tests;

use Illuminate\Support\Facades\Http;
use Interiordefine\Magento\Api\BundleProducts;
use Interiordefine\Magento\MagentoFacade;

class BundleProductsTest extends TestCase
{
    public function test_can_call_bundle_products()
    {
        $this->assertInstanceOf(BundleProducts::class, MagentoFacade::api('bundleProducts'));
    }

    public function test_can_call_bundle_products_options()
    {
        Http::fake([
            '*rest/all/V1/bundle-products/foo/options/all' => Http::response([], 200),
        ]);

        $api = MagentoFacade::api('bundleProducts')->options('foo');

        $this->assertTrue($api->ok());
    }
}

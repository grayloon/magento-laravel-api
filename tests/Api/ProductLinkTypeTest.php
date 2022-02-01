<?php

namespace Interiordefine\Magento\Tests;

use Illuminate\Support\Facades\Http;
use Interiordefine\Magento\Api\ProductLinkType;
use Interiordefine\Magento\MagentoFacade;

class ProductLinkTypeTest extends TestCase
{
    public function test_can_call_product_link_type()
    {
        $this->assertInstanceOf(ProductLinkType::class, MagentoFacade::api('productLinkType'));
    }

    public function test_can_call_product_link_type_types()
    {
        Http::fake();

        $api = MagentoFacade::api('productLinkType')->types();

        $this->assertTrue($api->ok());
    }
}

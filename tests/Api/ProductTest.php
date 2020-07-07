<?php

namespace Grayloon\Magento\Tests;

use Grayloon\Magento\Magento;
use Grayloon\Magento\Api\Products;
use Illuminate\Support\Facades\Http;

class ProductTest extends TestCase
{
    public function test_can_call_magento_api_products()
    {
        $magento = new Magento();

        $this->assertInstanceOf(Products::class, $magento->api('products'));
    }

    public function test_can_call_magento_api_products_all()
    {
        Http::fake();

        $magento = new Magento();

        $this->assertNull($magento->api('products')->all());
    }
}

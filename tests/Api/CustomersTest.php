<?php

namespace Grayloon\Magento\Tests;

use Grayloon\Magento\Api\Customers;
use Grayloon\Magento\Magento;
use Illuminate\Support\Facades\Http;

class CustomersTest extends TestCase
{
    public function test_can_call_magento_api_customers()
    {
        $magento = new Magento();

        $this->assertInstanceOf(Customers::class, $magento->api('customers'));
    }

    public function test_can_call_magento_api_customers_all()
    {
        Http::fake();

        $magento = new Magento();

        $this->assertNull($magento->api('customers')->all());
    }
}

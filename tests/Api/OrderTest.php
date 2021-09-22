<?php

namespace Grayloon\Magento\Tests;

use Grayloon\Magento\Api\Order;
use Grayloon\Magento\MagentoFacade;
use Illuminate\Support\Facades\Http;

class OrderTest extends TestCase
{
    public function test_can_call_magento_api_orders()
    {
        $this->assertInstanceOf(Order::class, MagentoFacade::api('order'));
    }

    public function test_can_call_magento_api_order_invoice()
    {
        Http::fake([
            '*rest/all/V1/order/1/invoice' => Http::response([], 200),
        ]);

        $api = MagentoFacade::api('order')->invoice(1);

        $this->assertTrue($api->ok());
    }

    public function test_can_call_magento_api_order_invoice_with_parameters()
    {
        Http::fake([
            '*rest/all/V1/order/1/invoice' => Http::response([], 200),
        ]);

        $api = MagentoFacade::api('order')->invoice(1,true,true,true,'','',0);

        $this->assertTrue($api->ok());
    }
}

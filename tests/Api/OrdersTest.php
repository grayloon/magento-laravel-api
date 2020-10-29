<?php

namespace Grayloon\Magento\Tests;

use Grayloon\Magento\Api\Orders;
use Grayloon\Magento\MagentoFacade;
use Illuminate\Support\Facades\Http;

class OrdersTest extends TestCase
{
    public function test_can_call_magento_api_orders()
    {
        $this->assertInstanceOf(Orders::class, MagentoFacade::api('orders'));
    }

    public function test_can_call_magento_api_orders_all()
    {
        Http::fake([
            '*rest/all/V1/orders*' => Http::response([], 200),
        ]);

        $api = MagentoFacade::api('orders')->all();

        $this->assertTrue($api->ok());
    }

    public function test_can_call_magento_api_orders_all_with_filter()
    {
        Http::fake([
            '*rest/all/V1/orders*' => Http::response([], 200),
        ]);

        $api = MagentoFacade::api('orders')->all(1, 1, [
            'searchCriteria[filterGroups][0][filters][0][field]' => 'customer_email',
            'searchCriteria[filterGroups][0][filters][0][value]' => 'foo@bar.com',
        ]);

        $this->assertTrue($api->ok());
    }
}

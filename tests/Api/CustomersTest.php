<?php

namespace Grayloon\Magento\Tests;

use Grayloon\Magento\Api\Customers;
use Grayloon\Magento\MagentoFacade;
use Illuminate\Support\Facades\Http;

class CustomersTest extends TestCase
{
    public function test_can_call_magento_api_customers()
    {
        $this->assertInstanceOf(Customers::class, MagentoFacade::api('customers'));
    }

    public function test_can_call_magento_api_customers_all()
    {
        Http::fake();

        $api = MagentoFacade::api('customers')->all();

        $this->assertTrue($api->ok());
    }

    public function test_can_call_magento_api_customers_all_with_filter()
    {
        Http::fake();

        $api = MagentoFacade::api('customers')->all(1, 1, [
            'searchCriteria[filterGroups][0][filters][0][field]' => 'email',
            'searchCriteria[filterGroups][0][filters][0][value]' => 'foo@bar.com',
        ]);

        $this->assertTrue($api->ok());
    }
}

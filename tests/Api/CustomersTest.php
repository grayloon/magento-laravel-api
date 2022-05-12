<?php

namespace Interiordefine\Magento\Tests;

use Illuminate\Support\Facades\Http;
use Interiordefine\Magento\Api\Customers;
use Interiordefine\Magento\MagentoFacade;

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

        $api = MagentoFacade::api('customers')->all([
            'searchCriteria[filterGroups][0][filters][0][field]' => 'email',
            'searchCriteria[filterGroups][0][filters][0][value]' => 'foo@bar.com',
        ], 1, 1);

        $this->assertTrue($api->ok());
    }

    public function test_can_create_customer()
    {
        Http::fake([
            '*rest/all/V1/customers' => Http::response([], 200),
        ]);

        $api = MagentoFacade::api('customers')->create(['foo' => 'bar']);

        $this->assertTrue($api->ok());
    }

    public function test_can_request_password_reset_link()
    {
        Http::fake([
            '*rest/all/V1/customers/password' => Http::response([], 200),
        ]);

        $api = MagentoFacade::api('customers')->password('foo@bar.com', 'default', 1);

        $this->assertTrue($api->ok());
    }

    public function test_can_reset_password()
    {
        Http::fake([
            '*rest/all/V1/customers/resetPassword' => Http::response([], 200),
        ]);

        $api = MagentoFacade::api('customers')->resetPassword('foo@bar.com', 'fake_token', 'password');

        $this->assertTrue($api->ok());
    }

    public function test_can_customer_show()
    {
        Http::fake([
            '*rest/all/V1/customers/1' => Http::response([], 200),
        ]);

        $api = MagentoFacade::api('customers')->show(1);

        $this->assertTrue($api->ok());
    }
}

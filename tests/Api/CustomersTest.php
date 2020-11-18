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
}

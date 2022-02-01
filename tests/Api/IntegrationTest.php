<?php

namespace Interiordefine\Magento\Tests;

use Illuminate\Support\Facades\Http;
use Interiordefine\Magento\Api\Integration;
use Interiordefine\Magento\MagentoFacade;

class IntegrationTest extends TestCase
{
    public function test_can_call_magento_api_integration()
    {
        $this->assertInstanceOf(Integration::class, MagentoFacade::api('integration'));
    }

    public function test_can_call_magento_api_integration_customer_token()
    {
        Http::fake();

        $api = MagentoFacade::api('integration')->customerToken('foo@bar.com', 'secret');

        $this->assertTrue($api->ok());
    }

    public function test_can_call_magento_api_integration_admin_token()
    {
        Http::fake();

        $api = MagentoFacade::api('integration')->adminToken('admin', 'secret');

        $this->assertTrue($api->ok());
    }
}

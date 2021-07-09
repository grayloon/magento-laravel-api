<?php

namespace Grayloon\Magento\Tests;

use Grayloon\Magento\Api\Store;
use Grayloon\Magento\MagentoFacade;
use Illuminate\Support\Facades\Http;

class StoreTest extends TestCase
{
    public function test_can_instantiate_store()
    {
        $this->assertInstanceOf(Store::class, MagentoFacade::api('store'));
    }

    public function test_can_call_store_store_configs()
    {
        Http::fake([
            '*rest/all/V1/store/storeConfigs*' => Http::response([], 200),
        ]);

        $api = MagentoFacade::api('store')->storeConfigs();

        $this->assertTrue($api->ok());
    }
}

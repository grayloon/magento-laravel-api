<?php

namespace Interiordefine\Magento\Tests;

use Illuminate\Support\Facades\Http;
use Interiordefine\Magento\Api\Store;
use Interiordefine\Magento\MagentoFacade;

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

    public function test_can_call_stores_websites()
    {
        Http::fake([
            '*rest/all/V1/store/websites*' => Http::response([], 200),
        ]);

        $api = MagentoFacade::api('store')->websites();

        $this->assertTrue($api->ok());
    }
}

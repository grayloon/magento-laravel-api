<?php

namespace Interiordefine\Magento\Tests;

use Illuminate\Support\Facades\Http;
use Interiordefine\Magento\Api\Sources;
use Interiordefine\Magento\MagentoFacade;

class SourcesTest extends TestCase
{
    public function test_can_instantiate_sources()
    {
        $this->assertInstanceOf(Sources::class, MagentoFacade::api('sources'));
    }

    public function test_can_call_sources_all()
    {
        Http::fake([
            '*rest/all/V1/inventory/sources*' => Http::response([], 200),
        ]);

        $api = MagentoFacade::api('sources')->all();

        $this->assertTrue($api->ok());
    }

    public function test_can_call_sources_by_source_name()
    {
        Http::fake([
            '*rest/all/V1/inventory/sources/default*' => Http::response([], 200),
        ]);

        $api = MagentoFacade::api('sources')->bySourceName();

        $this->assertTrue($api->ok());
    }
}

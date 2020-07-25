<?php

namespace Grayloon\Magento\Tests;

use Grayloon\Magento\Api\Schema;
use Grayloon\Magento\Magento;
use Illuminate\Support\Facades\Http;

class SchemaTest extends TestCase
{
    public function test_can_call_magento_api_schema()
    {
        $magento = new Magento();

        $this->assertInstanceOf(Schema::class, $magento->api('schema'));
    }

    public function test_can_call_magento_api_schema_show()
    {
        Http::fake();

        $magento = new Magento();
        $api = $magento->api('schema')->show();

        $this->assertTrue($api->ok());
    }
}

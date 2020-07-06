<?php

namespace Grayloon\Magento\Tests;

use Grayloon\Magento\Magento;
use Grayloon\Magento\Api\Schema;
use Grayloon\Magento\Tests\TestCase;
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

        $this->assertNull($magento->api('schema')->show());
    }
}

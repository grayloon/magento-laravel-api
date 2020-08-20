<?php

namespace Grayloon\Magento\Tests;

use Grayloon\Magento\Api\SourceItems;
use Grayloon\Magento\Magento;
use Illuminate\Support\Facades\Http;

class SourceItemsTest extends TestCase
{
    public function test_can_instantiate_source_items_from_api()
    {
        $magento = new Magento();

        $this->assertInstanceOf(SourceItems::class, $magento->api('sourceItems'));
    }

    public function test_can_call_source_items_all()
    {
        Http::fake();

        $magento = new Magento();

        $api = $magento->api('sourceItems')->all();

        $this->assertTrue($api->ok());
    }
}

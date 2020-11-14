<?php

namespace Grayloon\Magento\Tests;

use Grayloon\Magento\Api\Stocks;
use Grayloon\Magento\Api\Sources;
use Grayloon\Magento\MagentoFacade;
use Illuminate\Support\Facades\Http;

class StocksTest extends TestCase
{
    public function test_can_instantiate_stocks()
    {
        $this->assertInstanceOf(Stocks::class, MagentoFacade::api('stocks'));
    }

    public function test_can_call_stocks_all()
    {
        Http::fake([
            '*rest/all/V1/inventory/stocks*' => Http::response([], 200),
        ]);

        $api = MagentoFacade::api('stocks')->all();

        $this->assertTrue($api->ok());
    }
}

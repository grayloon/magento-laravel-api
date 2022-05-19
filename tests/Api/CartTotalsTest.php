<?php

namespace Grayloon\Magento\Tests;

use Grayloon\Magento\Api\CartTotals;
use Grayloon\Magento\MagentoFacade;
use Illuminate\Support\Facades\Http;

class CartTotalsTest extends TestCase
{
    public function test_can_call_cart_totals()
    {
        $this->assertInstanceOf(CartTotals::class, MagentoFacade::api('cartTotals'));
    }

    public function test_can_call_cart_totals_mine()
    {
        Http::fake();

        $api = MagentoFacade::setStoreCode('default')->api('cartTotals')->mine();

        $this->assertTrue($api->ok());
    }

    public function test_must_pass_a_single_store_code_to_cart_totals_mine()
    {
        $this->expectException('exception');

        MagentoFacade::api('cartTotals')->mine();
    }
}

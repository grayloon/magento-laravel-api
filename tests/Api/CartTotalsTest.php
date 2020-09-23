<?php

namespace Grayloon\Magento\Tests;

use Grayloon\Magento\Api\CartTotals;
use Grayloon\Magento\Magento;
use Illuminate\Support\Facades\Http;

class CartTotalsTest extends TestCase
{
    public function test_can_call_cart_totals()
    {
        $this->assertInstanceOf(CartTotals::class, (new Magento())->api('cartTotals'));
    }

    public function test_can_call_cart_totals_mine()
    {
        Http::fake();

        $magento = new Magento();
        $magento->storeCode = 'def
        ault';

        $api = $magento->api('cartTotals')->mine();

        $this->assertTrue($api->ok());
    }

    public function test_must_pass_a_single_store_code_to_cart_totals_mine()
    {
        $this->expectException('exception');

        $magento = new Magento();
        $magento->api('cartTotals')->mine();
    }
}

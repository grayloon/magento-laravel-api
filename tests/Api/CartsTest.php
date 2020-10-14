<?php

namespace Grayloon\Magento\Tests;

use Grayloon\Magento\Api\Carts;
use Grayloon\Magento\MagentoFacade;
use Illuminate\Support\Facades\Http;

class CartsTest extends TestCase
{
    public function test_can_call_carts()
    {
        $this->assertInstanceOf(Carts::class, MagentoFacade::api('carts'));
    }

    public function test_can_call_carts_mine()
    {
        Http::fake();

        $api = MagentoFacade::setStoreCode('default')->api('carts')->mine();

        $this->assertTrue($api->ok());
    }

    public function test_must_pass_a_single_store_code_to_carts_mine()
    {
        $this->expectException('exception');

        MagentoFacade::api('carts')->mine();
    }

    public function test_can_call_carts_estimate_shipping_methods()
    {
        Http::fake([
            '*rest/default/V1/carts/mine/estimate-shipping-methods' => Http::response([], 200),
        ]);

        $api = MagentoFacade::setStoreCode('default')->api('carts')->estimateShippingMethods([]);

        $this->assertTrue($api->ok());
    }

    public function test_must_pass_a_single_store_code_to_estimate_shipping_methods()
    {
        $this->expectException('exception');

        MagentoFacade::api('carts')->estimateShippingMethods([]);
    }

    public function test_can_call_carts_totals_information()
    {
        Http::fake([
            '*rest/default/V1/carts/mine/totals-information' => Http::response([], 200),
        ]);

        $api = MagentoFacade::setStoreCode('default')->api('carts')->totalsInformation([]);

        $this->assertTrue($api->ok());
    }

    public function test_must_pass_a_single_store_code_to_totals_information()
    {
        $this->expectException('exception');

        MagentoFacade::api('carts')->totalsInformation([]);
    }

    public function test_can_call_carts_shipping_information()
    {
        Http::fake([
            '*rest/default/V1/carts/mine/shipping-information' => Http::response([], 200),
        ]);

        $api = MagentoFacade::setStoreCode('default')->api('carts')->shippingInformation([]);

        $this->assertTrue($api->ok());
    }

    public function test_must_pass_a_single_store_code_to_shipping_information()
    {
        $this->expectException('exception');

        MagentoFacade::api('carts')->shippingInformation([]);
    }

    public function test_can_call_carts_payment_methods()
    {
        Http::fake([
            '*rest/default/V1/carts/mine/payment-methods' => Http::response([], 200),
        ]);

        $api = MagentoFacade::setStoreCode('default')->api('carts')->paymentMethods([]);

        $this->assertTrue($api->ok());
    }

    public function test_must_pass_a_single_store_code_to_payment_methods()
    {
        $this->expectException('exception');

        MagentoFacade::api('carts')->paymentMethods([]);
    }

    public function test_can_call_carts_payment_information()
    {
        Http::fake([
            '*rest/default/V1/carts/mine/payment-information' => Http::response([], 200),
        ]);

        $api = MagentoFacade::setStoreCode('default')->api('carts')->paymentInformation([]);

        $this->assertTrue($api->ok());
    }

    public function test_can_call_cart_mine_create()
    {
        Http::fake([
            '*rest/default/V1/carts/mine' => Http::response('foo', 200),
        ]);

        $api = MagentoFacade::setStoreCode('default')->api('carts')->create();

        $this->assertTrue($api->ok());
    }

    public function test_must_pass_a_single_store_code_to_payment_information()
    {
        $this->expectException('exception');

        MagentoFacade::api('carts')->paymentInformation([]);
    }
}

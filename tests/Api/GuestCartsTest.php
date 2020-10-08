<?php

namespace Grayloon\Magento\Tests;

use Grayloon\Magento\Api\GuestCarts;
use Grayloon\Magento\MagentoFacade;
use Illuminate\Support\Facades\Http;

class GuestCartsTest extends TestCase
{
    public function test_can_call_guest_carts()
    {
        $this->assertInstanceOf(GuestCarts::class, MagentoFacade::api('guestCarts'));
    }

    public function test_can_call_guest_carts_create()
    {
        Http::fake();

        $api = MagentoFacade::api('guestCarts')->create();

        $this->assertTrue($api->ok());
    }

    public function test_can_call_guest_carts_cart()
    {
        Http::fake();

        $api = MagentoFacade::api('guestCarts')->cart('foo');

        $this->assertTrue($api->ok());
    }

    public function test_can_call_guest_carts_items()
    {
        Http::fake();

        $api = MagentoFacade::api('guestCarts')->items('foo');

        $this->assertTrue($api->ok());
    }

    public function test_can_call_guest_carts_add_item()
    {
        Http::fake();

        $api = MagentoFacade::api('guestCarts')->addItem('foo', 'bar', 1);

        $this->assertTrue($api->ok());
    }

    public function test_can_call_guest_carts_totals()
    {
        Http::fake();

        $api = MagentoFacade::api('guestCarts')->totals('foo');

        $this->assertTrue($api->ok());
    }

    public function test_can_call_guest_carts_estimate_shipping_methods()
    {
        Http::fake([
            '*rest/all/V1/guest-carts/foo/estimate-shipping-methods' => Http::response([], 200),
        ]);

        $api = MagentoFacade::api('guestCarts')->estimateShippingMethods('foo');

        $this->assertTrue($api->ok());
    }

    public function test_can_call_guest_carts_totals_information()
    {
        Http::fake([
            '*rest/all/V1/guest-carts/foo/totals-information' => Http::response([], 200),
        ]);

        $api = MagentoFacade::api('guestCarts')->totalsInformation('foo');

        $this->assertTrue($api->ok());
    }

    public function test_can_call_guest_carts_shipping_information()
    {
        Http::fake([
            '*rest/all/V1/guest-carts/foo/shipping-information' => Http::response([], 200),
        ]);

        $api = MagentoFacade::api('guestCarts')->shippingInformation('foo');

        $this->assertTrue($api->ok());
    }

    public function test_can_call_guest_carts_payment_methods()
    {
        Http::fake([
            '*rest/all/V1/guest-carts/foo/payment-methods' => Http::response([], 200),
        ]);

        $api = MagentoFacade::api('guestCarts')->paymentMethods('foo');

        $this->assertTrue($api->ok());
    }

    public function test_can_call_guest_carts_payment_information()
    {
        Http::fake([
            '*rest/all/V1/guest-carts/foo/payment-information' => Http::response([], 200),
        ]);

        $api = MagentoFacade::api('guestCarts')->paymentInformation('foo', ['bar']);

        $this->assertTrue($api->ok());
    }
}

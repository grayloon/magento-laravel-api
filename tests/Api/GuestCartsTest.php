<?php

namespace Grayloon\Magento\Tests;

use Grayloon\Magento\Api\GuestCarts;
use Grayloon\Magento\Magento;
use Illuminate\Support\Facades\Http;

class GuestCartsTest extends TestCase
{
    public function test_can_call_guest_carts()
    {
        $magento = new Magento();

        $this->assertInstanceOf(GuestCarts::class, $magento->api('guestCarts'));
    }

    public function test_can_call_guest_carts_create()
    {
        Http::fake();

        $magento = new Magento();

        $api = $magento->api('guestCarts')->create();

        $this->assertTrue($api->ok());
    }

    public function test_can_call_guest_carts_cart()
    {
        Http::fake();

        $magento = new Magento();

        $api = $magento->api('guestCarts')->cart('foo');

        $this->assertTrue($api->ok());
    }

    public function test_can_call_guest_carts_items()
    {
        Http::fake();

        $magento = new Magento();

        $api = $magento->api('guestCarts')->items('foo');

        $this->assertTrue($api->ok());
    }

    public function test_can_call_guest_carts_add_item()
    {
        Http::fake();

        $magento = new Magento();

        $api = $magento->api('guestCarts')->addItem('foo', 'bar', 1);

        $this->assertTrue($api->ok());
    }

    public function test_can_call_guest_carts_totals()
    {
        Http::fake();

        $magento = new Magento();

        $api = $magento->api('guestCarts')->totals('foo');

        $this->assertTrue($api->ok());
    }
}

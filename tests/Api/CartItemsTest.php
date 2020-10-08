<?php

namespace Grayloon\Magento\Tests;

use Grayloon\Magento\Api\CartItems;
use Grayloon\Magento\Magento;
use Grayloon\Magento\MagentoFacade;
use Illuminate\Support\Facades\Http;

class CartItemsTest extends TestCase
{
    public function test_can_call_cart_items()
    {
        $this->assertInstanceOf(CartItems::class, MagentoFacade::api('CartItems'));
    }

    public function test_can_call_cart_item_mine()
    {
        Http::fake();

        $api = MagentoFacade::setStoreCode('default')->api('cartItems')->mine();

        $this->assertTrue($api->ok());
    }

    public function test_must_pass_a_single_store_code_to_cart_items_mine()
    {
        $this->expectException('exception');

        MagentoFacade::api('cartItems')->mine();
    }

    public function test_can_call_cart_item_add_item()
    {
        Http::fake();

        $api = MagentoFacade::setStoreCode('default')->api('cartItems')->addItem('foo', 'bar', 1);

        $this->assertTrue($api->ok());
    }

    public function test_must_pass_a_single_store_code_to_cart_items_add_item()
    {
        $this->expectException('exception');

        MagentoFacade::api('cartItems')->addItem('foo', 'bar', 1);
    }
}

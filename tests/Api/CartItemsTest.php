<?php

namespace Interiordefine\Magento\Tests;

use Illuminate\Support\Facades\Http;
use Interiordefine\Magento\Api\CartItems;
use Interiordefine\Magento\MagentoFacade;

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

    public function test_can_call_cart_item_remove_item()
    {
        Http::fake([
            '*rest/default/V1/carts/mine/items/1' => Http::response([], 200),
        ]);

        $api = MagentoFacade::setStoreCode('default')->api('cartItems')->removeItem(1);

        $this->assertTrue($api->ok());
    }

    public function test_can_edit_cart_item()
    {
        Http::fake([
            '*rest/default/V1/carts/mine/items/foo' => Http::response([], 200),
        ]);

        $api = MagentoFacade::setStoreCode('default')->api('cartItems')->editItem('foo', []);

        $this->assertTrue($api->ok());
    }
}

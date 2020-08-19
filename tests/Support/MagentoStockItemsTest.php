<?php

namespace Grayloon\Magento\Tests\Support;

use Grayloon\Magento\Models\MagentoProduct;
use Grayloon\Magento\Models\MagentoStockItem;
use Grayloon\Magento\Support\MagentoStockItems;
use Grayloon\Magento\Tests\TestCase;

class MagentoStockItemsTest extends TestCase
{
    public function test_can_add_stock_item()
    {
        $product = factory(MagentoProduct::class)->create();

        $response = [
            'stock_item' => [
                'product_id'  => $product->id,
                'item_id'     => $product->id,
                'stock_id'    => 1,
                'qty'         => 250,
                'is_in_stock' => true,
            ],
        ];

        (new MagentoStockItems())->updateItemStock($response);

        $this->assertEquals(1, MagentoStockItem::count());
        $this->assertEquals($product->id, MagentoStockItem::first()->product_id);
        $this->assertEquals($product->id, MagentoStockItem::first()->item_id);
        $this->assertEquals(1, MagentoStockItem::first()->stock_id);
        $this->assertEquals(250, MagentoStockItem::first()->qty);
        $this->assertEquals(1, MagentoStockItem::first()->is_in_stock);
    }

    public function test_empty_response_skips_creation()
    {
        (new MagentoStockItems)->updateItemStock([]);
        $this->assertEquals(0, MagentoStockItem::count());
    }

    public function test_missing_stock_item_skips_creation()
    {
        (new MagentoStockItems)->updateItemStock(['foo' => 'bar']);
        $this->assertEquals(0, MagentoStockItem::count());
    }

    public function test_invalid_stock_item_data_skips_creation()
    {
        (new MagentoStockItems)->updateItemStock([
            'stock_item' => [
                'foo' => 'bar',
            ],
        ]);
        $this->assertEquals(0, MagentoStockItem::count());
    }

    public function test_can_update_stock_item()
    {
        $product = factory(MagentoProduct::class)->create();
        $stock = factory(MagentoStockItem::class)->create([
            'product_id'  => $product->id,
            'item_id'     => $product->id,
            'stock_id'    => 1,
            'qty'         => 0,
            'is_in_stock' => false,
        ]);

        $response = [
            'stock_item' => [
                'product_id'  => $product->id,
                'item_id'     => $product->id,
                'stock_id'    => 1,
                'qty'         => 250,
                'is_in_stock' => true,
            ],
        ];

        (new MagentoStockItems())->updateItemStock($response);

        $this->assertEquals(1, MagentoStockItem::count());
        $this->assertEquals($product->id, MagentoStockItem::first()->product_id);
        $this->assertEquals($product->id, MagentoStockItem::first()->item_id);
        $this->assertEquals(1, MagentoStockItem::first()->stock_id);
        $this->assertEquals(250, MagentoStockItem::first()->qty);
        $this->assertEquals(1, MagentoStockItem::first()->is_in_stock);
    }
}

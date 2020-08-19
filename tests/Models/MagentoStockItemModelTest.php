<?php

namespace Grayloon\Magento\Tests;

use Grayloon\Magento\Models\MagentoProduct;
use Grayloon\Magento\Models\MagentoStockItem;

class MagentoStockItemModelTest extends TestCase
{
    public function test_can_create_magento_stock_item()
    {
        $item = factory(MagentoStockItem::class)->create();

        $this->assertNotEmpty($item);
    }

    public function test_magento_stock_item_product_id_belongs_to_product()
    {
        $product = factory(MagentoProduct::class)->create();
        $stock = factory(MagentoStockItem::class)->create([
            'product_id' => $product->id,
        ]);

        $this->assertNotEmpty($stock->product_id);
        $this->assertEquals($stock->product->id, $stock->product_id);
        $this->assertInstanceOf(MagentoProduct::class, $stock->product);
        $this->assertEquals($stock->product->id, $product->id);
    }
}

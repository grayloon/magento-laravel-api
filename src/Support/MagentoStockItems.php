<?php

namespace Grayloon\Magento\Support;

use Grayloon\Magento\Models\MagentoStockItem;

class MagentoStockItems
{
    /**
     * Updates the product stock items.
     *
     * @param  array  $response
     * @return void
     */
    public function updateItemStock($response)
    {
        if (! $response || ! isset($response['stock_item']) || ! isset($response['stock_item']['product_id'])) {
            return;
        }

        $stock = $response['stock_item'];

        MagentoStockItem::updateOrCreate([
            'product_id' => $stock['product_id'],
            'item_id'    => $stock['item_id'],
        ], [
            'stock_id'    => $stock['stock_id'],
            'qty'         => $stock['qty'],
            'is_in_stock' => $stock['is_in_stock'],
            'synced_at'   => now(),
        ]);
    }
}

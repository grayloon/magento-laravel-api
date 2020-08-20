<?php

namespace Grayloon\Magento\Support;

use Grayloon\Magento\Jobs\SyncMagentoStockItems;
use Grayloon\Magento\Models\MagentoProduct;
use Grayloon\Magento\Models\MagentoProductLink;

class MagentoProductLinks
{
    /**
     * Updates the product links from the Api response.
     *
     * @param  Grayloon\Magento\Models\MagentoProduct  $product
     * @param  array  $response
     * @return void
     */
    public function updateProductLinks($product, $response)
    {
        if (! $response || ! isset($response['product_links']) || ! $response['product_links']) {
            return;
        }

        foreach ($response['product_links'] as $link) {
            $productLink = MagentoProduct::where('sku', $link['linked_product_sku'])->first();

            // If the relating product doesn't exist yet, relaunch the job to check again later.
            if (! $productLink) {
                SyncMagentoStockItems::dispatch($product, $response);
                continue;
            }

            MagentoProductLink::updateOrCreate([
                'product_id' => $product->id,
                'related_product_id' => $productLink->id,
                'link_type' => $link['link_type'],
            ], [
                'position' => $link['position'],
                'synced_at'   => now(),
            ]);
        }
    }
}

<?php

namespace Grayloon\Magento\Jobs;

use Grayloon\Magento\Magento;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Grayloon\Magento\Models\MagentoProduct;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Grayloon\Magento\Models\MagentoExtAttribute;
use Grayloon\Magento\Models\MagentoCustAttribute;
use Grayloon\Magento\Models\MagentoExtAttributeType;
use Grayloon\Magento\Models\MagentoCustAttributeType;

class SyncMagentoProductsBatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $pageSize;
    public $requestedPage;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($pageSize, $requestedPage)
    {
        $this->pageSize = $pageSize;
        $this->requestedPage = $requestedPage;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $products = Magento::api('products')->all($this->pageSize, $this->requestedPage);

        foreach ($products['items'] as $product) {
            $magentoProduct = MagentoProduct::updateOrCreate(['id' => $product['id']], [
                'id'                   => $product['id'],
                'name'                 => $product['name'],
                'sku'                  => $product['sku'],
                'price'                => $product['price'] ?? 0,
                'status'               => $product['status'],
                'visibility'           => $product['visibility'],
                'type'                 => $product['type_id'],
                'created_at'           => $product['created_at'],
                'updated_at'           => $product['updated_at'],
                'weight'               => $product['weight'] ?? 0,
                'synced_at'            => now(),
            ]);

            foreach ($product['extension_attributes'] as $extAttributeKey => $extAttribute) {
                $attributeType = MagentoExtAttributeType::firstOrCreate(['type' => $extAttributeKey]);

                MagentoExtAttribute::updateOrCreate([
                    'magento_product_id'            => $magentoProduct->id,
                    'magento_ext_attribute_type_id' => $attributeType->id,
                ], ['attribute' => $extAttribute]);
            }

            foreach ($product['custom_attributes'] as $custAttribute) {
                $attributeType = MagentoCustAttributeType::firstOrCreate(['type' => $custAttribute['attribute_code']]);
                
                if (is_array($custAttribute['value'])) {
                    $custAttribute['value'] = json_encode($custAttribute['value']);
                }

                MagentoCustAttribute::updateOrCreate([
                    'magento_product_id'             => $magentoProduct->id,
                    'magento_cust_attribute_type_id' => $attributeType->id,
                ], ['attribute' => $custAttribute['value']]);
            }
        }
    }
}

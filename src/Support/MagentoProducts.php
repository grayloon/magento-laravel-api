<?php

namespace Grayloon\Magento\Support;

use Grayloon\Magento\Magento;
use Grayloon\Magento\Models\MagentoProduct;

class MagentoProducts extends PaginatableMagentoService
{
    use HasCustomAttributes, HasExtensionAttributes, HasProductLinks, HasMediaEntries, HasProductCategories;

    /**
     * The amount of total products.
     *
     * @return int
     */
    public function count()
    {
        $products = (new Magento())->api('products')
            ->all($this->pageSize, $this->currentPage)
            ->json();

        return $products['total_count'];
    }

    /**
     * Updates a product from the Magento API.
     *
     * @param  array  $apiProduct
     * @return Grayloon\Magento\Models\MagentoProduct
     */
    public function updateOrCreateProduct($apiProduct)
    {
        $product = MagentoProduct::updateOrCreate(['id' => $apiProduct['id']], [
            'id'          => $apiProduct['id'],
            'name'        => $apiProduct['name'],
            'sku'         => $apiProduct['sku'],
            'price'       => $apiProduct['price'] ?? 0,
            'quantity'    => $apiProduct['extension_attributes']['stock_item']['qty'] ?? 0,
            'is_in_stock' => $apiProduct['extension_attributes']['stock_item']['is_in_stock'] ?? false,
            'status'      => $apiProduct['status'],
            'visibility'  => $apiProduct['visibility'],
            'type'        => $apiProduct['type_id'],
            'created_at'  => $apiProduct['created_at'],
            'updated_at'  => $apiProduct['updated_at'],
            'weight'      => $apiProduct['weight'] ?? 0,
            'synced_at'   => now(),
        ]);

        $this->syncExtensionAttributes($apiProduct['extension_attributes'], $product);
        $this->syncCustomAttributes($apiProduct['custom_attributes'], $product);
        $this->syncProductLinks($apiProduct['product_links'], $product);
        $this->downloadProductImages($apiProduct['media_gallery_entries'] ?? [], $product);

        return $product;
    }

    /**
     * Check if Custom Attributes have applied rules to be applied.
     *
     * @param  array  $attribute
     * @param  \Grayloon\Magento\Models\MagentoProduct $product
     * @return void
     */
    protected function applyConditionalRules($attribute, $product)
    {
        if ($attribute['attribute_code'] === 'category_ids') {
            $this->syncProductCategories($attribute['value'], $product);
        }

        if ($attribute['attribute_code'] === 'url_key') {
            $product->update([
                'slug' => $attribute['value'],
            ]);
        }

        return $this;
    }
}

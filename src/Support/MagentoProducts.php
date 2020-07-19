<?php

namespace Grayloon\Magento\Support;

use Grayloon\Magento\Magento;
use Grayloon\Magento\Models\MagentoExtensionAttribute;
use Grayloon\Magento\Models\MagentoExtensionAttributeType;
use Grayloon\Magento\Models\MagentoProduct;

class MagentoProducts extends PaginatableMagentoService
{
    /**
     * The amount of total products.
     *
     * @return int
     */
    public function count()
    {
        $products = Magento::api('products')->all($this->pageSize, $this->currentPage);

        return $products['total_count'];
    }

    /**
     * Updates products from the Magento API.
     *
     * @param  array  $products
     * @return void
     */
    public function updateProducts($products)
    {
        if (empty($products)) {
            return;
        }

        foreach ($products as $apiProduct) {
            $this->updateProduct($apiProduct);
        }

        return $this;
    }

    /**
     * Updates a product from the Magento API.
     *
     * @param  array  $products
     * @return void
     */
    public function updateProduct($apiProduct)
    {
        $product = MagentoProduct::updateOrCreate(['id' => $apiProduct['id']], [
            'id'         => $apiProduct['id'],
            'name'       => $apiProduct['name'],
            'sku'        => $apiProduct['sku'],
            'price'      => $apiProduct['price'] ?? 0,
            'status'     => $apiProduct['status'],
            'visibility' => $apiProduct['visibility'],
            'type'       => $apiProduct['type_id'],
            'created_at' => $apiProduct['created_at'],
            'updated_at' => $apiProduct['updated_at'],
            'weight'     => $apiProduct['weight'] ?? 0,
            'synced_at'  => now(),
        ]);

        $this->syncExtensionAttributes($apiProduct['extension_attributes'], $product);
        $this->syncCustomAttributes($apiProduct['custom_attributes'], $product);

        return $product;
    }

    /**
     * Sync the Magento 2 Extension attributes with the Product.
     *
     * @param  array  $attributes
     * @param  \Grayloon\Magento\Models\MagentoProduct\ $product
     * @return void
     */
    protected function syncExtensionAttributes($attributes, $product)
    {
        foreach ($attributes as $key => $attribute) {
            $type = MagentoExtensionAttributeType::firstOrCreate(['type' => $key]);

            MagentoExtensionAttribute::updateOrCreate([
                'magento_product_id'            => $product->id,
                'magento_ext_attribute_type_id' => $type->id,
            ], ['attribute' => $attribute]);
        }

        return $this;
    }

    /**
     * Sync the Magento 2 Custom attributes with the Product.
     *
     * @param  array  $attributes
     * @param  \Grayloon\Magento\Models\MagentoProduct\ $product
     * @return void
     */
    protected function syncCustomAttributes($attributes, $product)
    {
        foreach ($attributes as $attribute) {
            if (is_array($attribute['value'])) {
                $attribute['value'] = json_encode($attribute['value']);
            }

            $product->customAttributes()->updateOrCreate(['attribute_type' => $attribute['attribute_code']], [
                'value' => $attribute['value'],
            ]);
        }

        return $this;
    }
}

<?php

namespace Grayloon\Magento\Support;

use Grayloon\Magento\Models\MagentoProductCategory;

trait HasProductCategories
{
    /**
     * Assign the Product Category IDs that belong to the product.
     *
     * @param  array  $categoryIds
     * @param  \Grayloon\Magento\Models\MagentoProduct\ $product
     * @return void
     */
    protected function syncProductCategories($categoryIds, $product)
    {
        if (! $categoryIds) {
            return;
        }

        foreach ($categoryIds as $categoryId) {
            MagentoProductCategory::updateOrCreate([
                'magento_product_id'  => $product->id,
                'magento_category_id' => $categoryId,
            ]);
        }

        return $this;
    }
}

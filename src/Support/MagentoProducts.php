<?php

namespace Grayloon\Magento\Support;

use Grayloon\Magento\Jobs\DownloadMagentoProductImage;
use Grayloon\Magento\Jobs\WaitForLinkedProductSku;
use Grayloon\Magento\Magento;
use Grayloon\Magento\Models\MagentoExtensionAttribute;
use Grayloon\Magento\Models\MagentoExtensionAttributeType;
use Grayloon\Magento\Models\MagentoProduct;
use Grayloon\Magento\Models\MagentoProductCategory;
use Grayloon\Magento\Models\MagentoProductLink;

class MagentoProducts extends PaginatableMagentoService
{
    use HasCustomAttributes;

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
     * @param  array  $products
     * @return void
     */
    public function updateOrCreateProduct($apiProduct)
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
        $this->syncProductLinks($apiProduct['product_links'], $product);

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
            $this->applyConditionalRules($attribute, $product);
            $type = $this->resolveCustomAttributeType($attribute['attribute_code']);
            $value = $this->resolveCustomAttributeValue($type, $attribute['value']);

            $product
                ->customAttributes()
                ->updateOrCreate(['attribute_type_id' => $type->id], [
                    'attribute_type' => $attribute['attribute_code'],
                    'value'          => $value,
                ]);
        }

        return $this;
    }

    /**
     * Sync the Magento 2 Related Links with the Product.
     *
     * @param  array  $attributes
     * @param  \Grayloon\Magento\Models\MagentoProduct  $product
     * @return void
     */
    protected function syncProductLinks($links, $product)
    {
        if (! $links) {
            return;
        }

        foreach ($links as $link) {
            $this->updateProductLink($link, $product);
        }
    }

    /**
     * Creates or updates a product link based on the related value.
     *
     * @param  array  $link
     * @param \Grayloon\Magento\Models\MagentoProduct  $product
     * @return void
     */
    public function updateProductLink($link, $product)
    {
        $productLink = MagentoProduct::where('sku', $link['linked_product_sku'])->first();

        if (! $productLink) {
            return WaitForLinkedProductSku::dispatch($product, $link);
        }

        MagentoProductLink::updateOrCreate([
            'product_id'         => $product->id,
            'related_product_id' => $productLink->id,
        ], [
            'link_type'   => $link['link_type'],
            'position'    => $link['position'],
            'synced_at'   => now(),
        ]);
    }

    /**
     * Determine if the Custom Attribute type is an image.
     *
     * @param  string  $attribute_type
     * @return bool
     */
    protected function isImageType($attribute_type)
    {
        $types = [
            'thumbnail',
            'image',
            'small_image',
        ];

        return in_array($attribute_type, $types);
    }

    /**
     * Launch a job to download an image to the Laravel application.
     *
     * @param  string  $image
     * @return void
     */
    protected function downloadImage($image)
    {
        if ($image === 'no_selection') {
            return;
        }

        DownloadMagentoProductImage::dispatch($image);
    }

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

        if ($this->isImageType($attribute['attribute_code'])) {
            $this->downloadImage($attribute['value']);
        }

        return $this;
    }
}

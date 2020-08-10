<?php

namespace Grayloon\Magento\Support;

use Grayloon\Magento\Magento;
use Grayloon\Magento\Models\MagentoCategory;
use Grayloon\Magento\Models\MagentoCustomAttributeType;

class MagentoCategories extends PaginatableMagentoService
{
    /**
     * The amount of total categories.
     *
     * @return int
     */
    public function count()
    {
        $categories = (new Magento())->api('categories')
            ->all($this->pageSize, $this->currentPage)
            ->json();

        return $categories['total_count'];
    }

    /**
     * Updates categories from the Magento API.
     *
     * @param  array  $categories
     * @return void
     */
    public function updateCategories($categories)
    {
        if (! $categories) {
            return;
        }

        foreach ($categories as $apiCategory) {
            $this->updateCategory($apiCategory);
        }

        return $this;
    }

    /**
     * Updates a category from the Magento API.
     *
     * @param  array  $apiCategory
     * @return \Grayloon\Magento\Models\MagentoCategory\
     */
    public function updateCategory($apiCategory)
    {
        $category = MagentoCategory::updateOrCreate(['id' => $apiCategory['id']], [
            'name'            => $apiCategory['name'],
            'slug'            => $this->findAttributeByKey('url_path', $apiCategory['custom_attributes']),
            'parent_id'       => $apiCategory['parent_id'] == 0 ? null : $apiCategory['parent_id'], // don't allow a parent ID of 0.
            'position'        => $apiCategory['position'],
            'is_active'       => $apiCategory['is_active'] ?? false,
            'level'           => $apiCategory['level'],
            'created_at'      => $apiCategory['created_at'],
            'updated_at'      => $apiCategory['updated_at'],
            'path'            => $apiCategory['path'],
            'include_in_menu' => $apiCategory['include_in_menu'],
            'synced_at'       => now(),
        ]);

        $this->syncCustomAttributes($apiCategory['custom_attributes'], $category);

        return $category;
    }

    /**
     * Get a value from the provided custom attributes.
     *
     * @param  array  $apiCategory
     * @return string
     */
    protected function findAttributeByKey($key, $attributes)
    {
        foreach ($attributes as $attribute) {
            if ($attribute['attribute_code'] === $key) {
                return $attribute['value'];
            }
        }

        // return null if we don't find a value.
    }

    /**
     * Sync the Magento Custom attributes with the Category.
     *
     * @param  array  $attributes
     * @param  \Grayloon\Magento\Models\MagentoCategory\ $category
     * @return void
     */
    protected function syncCustomAttributes($attributes, $category)
    {
        foreach ($attributes as $attribute) {
            $type = $this->resolveCustomAttributeType($attribute['attribute_code']);
            $value = $this->resolveCustomAttributeValue($type, $attribute['value']);

            $category
                ->customAttributes()
                ->updateOrCreate(['attribute_type_id' => $type->id], [
                    'attribute_type' => $attribute['attribute_code'],
                    'value'          => $value,
                ]);
        }

        return $this;
    }

    /**
     * Resolve the Custom Attribute Type by the Attribute Code.
     *
     * @param  string  $attributeCode
     * @return \Grayloon\Magento\Models\MagentoCustomAttributeType
     */
    protected function resolveCustomAttributeType($attributeCode)
    {
        $type = MagentoCustomAttributeType::where('name', $attributeCode)
            ->first();

        if (! $type) {
            $api = (new Magento())->api('productAttributes')
                ->show($attributeCode)
                ->json();

            $type = MagentoCustomAttributeType::create([
                'name'         => $attributeCode,
                'display_name' => $api['default_frontend_label'] ?? $attributeCode,
                'options'      => $api['options'] ?? [],
            ]);
        }

        return $type;
    }

    /**
     * Resolve the Custom Attribute Value by the provided options.
     *
     * @param  \Grayloon\Magento\Models\MagentoCustomAttributeType  $type
     * @param  string  $value;
     * @return string|null
     */
    protected function resolveCustomAttributeValue($type, $value)
    {
        if ($type->options) {
            foreach ($type->options as $option) {
                if ($option['value'] == $value) {
                    return $option['label'];
                }
            }
        }

        if (is_array($value)) {
            $value = json_encode($value);
        }

        return $value;
    }
}

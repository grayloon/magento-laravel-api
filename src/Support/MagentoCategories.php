<?php

namespace Grayloon\Magento\Support;

use Grayloon\Magento\Jobs\ResolveMagentoCategory;
use Grayloon\Magento\Magento;
use Grayloon\Magento\Models\MagentoCategory;
use Illuminate\Support\Str;

class MagentoCategories
{
    /**
     * Updates a category from the Magento API.
     *
     * @param  array  $apiCategory
     * @param  string  $parentSlug
     * @return \Grayloon\Magento\Models\MagentoCategory\
     */
    public function updateCategory($apiCategory)
    {
        $category = MagentoCategory::updateOrCreate(['id' => $apiCategory['id']], [
            'name'      => $apiCategory['name'],
            'slug'      => $this->resolveSlug($apiCategory),
            'parent_id' => $apiCategory['parent_id'],
            'position'  => $apiCategory['position'],
            'is_active' => $apiCategory['is_active'] ?? false,
            'level'     => $apiCategory['level'],
            'synced_at' => now(),
        ]);

        $this->resolveChildCategories($category, $apiCategory['children_data']);

        return $category;
    }
    
    /**
     * Resolve the URI based on the parent categories.
     *
     * @param  array  $apiCategory
     *
     * @return string
     */
    protected function resolveSlug($apiCategory)
    {
        return (isset($apiCategory['parent_slug']))
            ? $apiCategory['parent_slug'] .'/'. Str::slug($apiCategory['name'])
            : Str::slug($apiCategory['name']);
    }

    /**
     * If children categories are present, dispatch a job to resolve the children elements.
     *
     * @param  Grayloon\Magento\Models\MagentoCategory  $category
     * @param  array  $children
     * @return void
     */
    protected function resolveChildCategories($category, $children)
    {
        if (! empty($children)) {
            foreach ($children as $child) {
                ResolveMagentoCategory::dispatch(
                    array_merge(
                        $child,
                        ['parent_slug' => $category->slug]
                    )
                );
            }
        }

        return $this;
    }
}

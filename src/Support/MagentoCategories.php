<?php

namespace Grayloon\Magento\Support;

use Grayloon\Magento\Magento;
use Grayloon\Magento\Models\MagentoCategory;

class MagentoCategories extends PaginatableMagentoService
{
    /**
     * The amount of total categories.
     *
     * @return integer
     */
    public function count()
    {
        $categories = Magento::api('categories')->all($this->pageSize, $this->currentPage);
       
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
        if (empty($categories)) {
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
        return MagentoCategory::updateOrCreate(['id' => $apiCategory['id']], [
            'name'            => $apiCategory['name'],
            'parent_id'       => ($apiCategory['parent_id'] == 0) ? null : $apiCategory['parent_id'], // don't allow a parent ID of 0.
            'position'        => $apiCategory['position'],
            'is_active'       => $apiCategory['is_active'] ?? false,
            'level'           => $apiCategory['level'],
            'created_at'      => $apiCategory['created_at'],
            'updated_at'      => $apiCategory['updated_at'],
            'path'            => $apiCategory['path'],
            'include_in_menu' => $apiCategory['include_in_menu'],
            'synced_at'       => now(),
        ]);
    }
}

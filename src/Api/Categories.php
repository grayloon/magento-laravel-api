<?php

namespace Grayloon\Magento\Api;

class Categories extends AbstractApi
{
    /**
     * Retrieve list of categories.
     *
     * @param int $rootCategoryId
     * @param int $depth
     *
     * @return array
     */
    public function all($rootCategoryId = null, $depth = null)
    {
        return $this->get('/categories', [
            'rootCategoryId' => $rootCategoryId,
            'depth'          => $depth,
        ]);
    }
}

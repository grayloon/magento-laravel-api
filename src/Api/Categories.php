<?php

namespace Grayloon\Magento\Api;

class Categories extends AbstractApi
{
    /**
     * The list of categories.
     *
     * @param integer $pageSize
     * @param integer $currentPage
     *
     * @return  array
     */
    public function all($pageSize = 50, $currentPage = 1)
    {
        return $this->get('/categories/list', [
            'searchCriteria[pageSize]'    => $pageSize,
            'searchCriteria[currentPage]' => $currentPage,
        ]);
    }
}

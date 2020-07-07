<?php

namespace Grayloon\Magento\Api;

class Products extends AbstractApi
{
    /**
     * The list of Products.
     *
     * @param integer $pageSize
     * @param integer $currentPage
     *
     * @return  array
     */
    public function all($pageSize = 50, $currentPage = 1)
    {
        return $this->get('/products', [
            'searchCriteria[pageSize]'    => $pageSize,
            'searchCriteria[currentPage]' => $currentPage,
        ]);
    }
}

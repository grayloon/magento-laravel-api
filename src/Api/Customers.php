<?php

namespace Grayloon\Magento\Api;

class Customers extends AbstractApi
{
    /**
     * The list of customers.
     *
     * @param int $pageSize
     * @param int $currentPage
     *
     * @return  array
     */
    public function all($pageSize = 50, $currentPage = 1)
    {
        return $this->get('/customers/search', [
            'searchCriteria[pageSize]'    => $pageSize,
            'searchCriteria[currentPage]' => $currentPage,
        ]);
    }
}

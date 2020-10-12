<?php

namespace Grayloon\Magento\Api;

class Customers extends AbstractApi
{
    /**
     * The list of customers.
     *
     * @param  int  $pageSize
     * @param  int  $currentPage
     * @param  array  $filters
     *
     * @return  array
     */
    public function all($pageSize = 50, $currentPage = 1, $filters = [])
    {
        return $this->get('/customers/search', array_merge($filters, [
            'searchCriteria[pageSize]'    => $pageSize,
            'searchCriteria[currentPage]' => $currentPage,
        ]));
    }

    /**
     * Create customer account. Perform necessary business operations like sending email.
     *
     * @param  array  $body
     * @return array
     */
    public function create($body)
    {
        return $this->post('/customers', $body);
    }
}

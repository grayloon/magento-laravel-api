<?php

namespace Grayloon\Magento\Api;

class Orders extends AbstractApi
{
    /**
     * Lists orders that match specified search criteria.
     *
     * @param  int  $pageSize
     * @param  int  $currentPage
     * @param  array  $filters
     *
     * @return  array
     */
    public function all($pageSize = 50, $currentPage = 1, $filters = [])
    {
        return $this->get('/orders', array_merge($filters, [
            'searchCriteria[pageSize]'    => $pageSize,
            'searchCriteria[currentPage]' => $currentPage,
        ]));
    }

    /**
     * Loads a specified order.
     *
     * @param  int  $orderId
     *
     * @return  array
     */
    public function show($orderId)
    {
        return $this->get('/orders/'.$orderId);
    }

    /**
     * Edits and saves an order using the specified entity id within the body.
     *
     * @param array $entity
     * @return array
     */
    public function edit($entity = [])
    {
        return $this->post('/orders', [
            'entity' => $entity
        ]);
    }
}

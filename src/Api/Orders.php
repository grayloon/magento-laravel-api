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
     * @return array
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
     * @return array
     */
    public function show($orderId)
    {
        return $this->get('/orders/'.$orderId);
    }

    /**
     * Performs persist operations for a specified order.
     *
     * @see https://magento.redoc.ly/2.4.3-admin/tag/orders/#operation/salesOrderRepositoryV1SavePost
     *
     * @param  array  $entity
     * @return array
     */
    public function edit($entity = [])
    {
        return $this->post('/orders', [
            'entity' => $entity,
        ]);
    }
}

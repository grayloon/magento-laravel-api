<?php

namespace Interiordefine\Magento\Api;

use Exception;
use Illuminate\Http\Client\Response;

class Orders extends AbstractApi
{
    /**
     * Lists orders that match specified search criteria.
     *
     * @param array $filters
     * @param int $pageSize
     * @param int $currentPage
     * @return Response
     * @throws Exception
     */
    public function all(array $filters = [], int $pageSize = 50, int $currentPage = 1): Response
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
     * Loads a specified order.
     *
     * @param int $orderId
     * @return Response
     * @throws Exception
     */
    public function show(int $orderId): Response
    {
        return $this->get('/orders/'.$orderId);
    }

    /**
     * Loads a specified order by Increment ID.
     *
     * @param string $incrementId
     * @return Response
     * @throws Exception
     */
    public function showByIncrementId(string $incrementId): Response
    {
        $query = array('searchCriteria' => []);
        $query['searchCriteria']['filter_groups'] = array('0'=> []);
        $query['searchCriteria']['filter_groups'][0]['filters'] = array('0'=>[]);
        $query['searchCriteria']['filter_groups'][0]['filters'][0] =
            array(
                'field'=>'increment_id',
                'value' => $incrementId,
                'condition_type' => 'eq'
            );

        return $this->get('/orders',urldecode(http_build_query($query)));
    }

    /**
     * Performs persist operations for a specified order.
     *
     * @see https://magento.redoc.ly/2.4.3-admin/tag/orders/#operation/salesOrderRepositoryV1SavePost
     *
     * @param array $entity
     * @return Response
     * @throws Exception
     */
    public function edit(array $entity = []): Response
    {
        return $this->post('/orders', [
            'entity' => $entity,
        ]);
    }
}

<?php

namespace Grayloon\Magento\Api;

use Exception;
use Illuminate\Http\Client\Response;

class Creditmemos extends AbstractApi
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
        return $this->get('/creditmemos', array_merge($filters, [
            'searchCriteria[pageSize]'    => $pageSize,
            'searchCriteria[currentPage]' => $currentPage,
        ]));
    }


    /**
     * Loads a specified order.
     *
     * @param int $orderId
     * @return Response
     * @throws Exception
     */
    public function show(int $entityId): Response
    {
        return $this->get('/creditmemos/'.$entityId);
    }

    /**
     * Loads a specified creditmemo.
     *
     * @param  string $incrementId
     * @return array
     */
    public function showByIncrementId($incrementId) {

        $query = array('searchCriteria' => []);
        $query['searchCriteria']['filter_groups'] = array('0'=> []);
        $query['searchCriteria']['filter_groups'][0]['filters'] = array('0'=>[]);
        $query['searchCriteria']['filter_groups'][0]['filters'][0] =
            array(
                'field'=>'increment_id',
                'value' => $incrementId,
                'condition_type' => 'eq'
            );
#        $result = $this->get('/orders',urldecode(http_build_query($query)));
        $result = $this->get('/creditmemos',$query);
        $items = $result->json();
        if (isset($items['items'])) {
            return reset($items['items']);
        }
        return $result;
    }

}

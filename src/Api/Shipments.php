<?php

namespace Grayloon\Magento\Api;

class Shipments extends AbstractApi
{
    /**
     * Lists Shipment that match specified search criteria.
     *
     * @param  int  $pageSize
     * @param  int  $currentPage
     * @param  array  $filters
     * @return array
     */
    public function all($pageSize = 50, $currentPage = 1, $filters = [])
    {
        return $this->get('/shipments', array_merge($filters, [
            'searchCriteria[pageSize]'    => $pageSize,
            'searchCriteria[currentPage]' => $currentPage,
        ]));
    }

    /**
     * Loads a specified shipment
     *
     * @param  int  $shipmentId
     * @return array
     */
    public function show($shipmentId)
    {
        return $this->get('/shipment/'.$shipmentId);
    }

    /**
     * Performs persist operations for a specified shipment.
     *
     * @see https://magento.redoc.ly/2.4.3-admin/tag/shipment#operation/salesShipmentRepositoryV1SavePost
     *
     * @param  array  $entity
     * @return array
     */
    public function create($entity = [])
    {
        return $this->post('/shipment', [
            'entity' => $entity,
        ]);
    }

}

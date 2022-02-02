<?php

namespace Interiordefine\Magento\Api;

use Exception;
use Illuminate\Http\Client\Response;

class Products extends AbstractApi
{
    /**
     * The list of Products.
     *
     * https://magento.redoc.ly/2.4.3-admin/tag/products#operation/catalogProductRepositoryV1SavePost
     *
     * @param array $filters
     * @param int|null $pageSize
     * @param int $currentPage
     * @return Response
     * @throws Exception
     */
    public function all(array $filters = [], int $pageSize = null, int $currentPage = 1): Response
    {
        $params = array_merge($filters, [
            'searchCriteria[pageSize]'    => $pageSize,
            'searchCriteria[currentPage]' => $currentPage,
        ]);

        return $this->get('/products', $params);
    }

    /**
     * Get a list of Products that were last modified by a given date.
     *
     * @param string $date (with format Y-m-d. e.g. 2022-02-01)
     * @param int|null $pageSize
     * @param int $currentPage
     * @return Response
     * @throws Exception
     */
    public function lastModifiedByDate(string $date, int $pageSize = null, int $currentPage = 1): Response
    {
        $params = [
            'searchCriteria[filterGroups][0][filters][0][conditionType]' => 'gteq',
            'searchCriteria[filterGroups][0][filters][0][field]' => 'updated_at',
            'searchCriteria[filterGroups][0][filters][0][value]' => $date,
            'searchCriteria[pageSize]'    => $pageSize,
            'searchCriteria[currentPage]' => $currentPage,
        ];

        return $this->get('/products', $params);
    }

    /**
     * Get a list of Product Skus what were last modified by a given date.
     *
     * @param string $date (with format Y-m-d. e.g. 2022-02-01)
     * @param int|null $pageSize
     * @param int $currentPage
     * @return Response
     * @throws Exception
     */
    public function getSkusLastModifiedByDate(string $date, int $pageSize = null, int $currentPage = 1): Response
    {
        $params = [
            'fields' => 'items[sku]',
            'searchCriteria[filterGroups][0][filters][0][conditionType]' => 'gteq',
            'searchCriteria[filterGroups][0][filters][0][field]' => 'updated_at',
            'searchCriteria[filterGroups][0][filters][0][value]' => $date,
            'searchCriteria[pageSize]'    => $pageSize,
            'searchCriteria[currentPage]' => $currentPage,
        ];

        return $this->get('/products', $params);
    }

    /**
     * Get info about product by product SKU.
     *
     * @param string $sku
     * @return Response
     * @throws Exception
     */
    public function getBySku(string $sku): Response
    {
        return $this->get('/products/'.$sku);
    }


    /**
     * Get info about product by product ID.
     *
     * @param  int  $id
     * @return array
     */
    public function getById($id)
    {
        $query = array('searchCriteria' => []);
        $query['searchCriteria']['filter_groups'] = array('0'=> []);
        $query['searchCriteria']['filter_groups'][0]['filters'] = array('0'=>[]);
        $query['searchCriteria']['filter_groups'][0]['filters'][0] =
            array(
                'field'=>'entity_id',
                'value' => $id,
                'condition_type' => 'eq'
            );
        return $this->get('/products',urldecode(http_build_query($query)));
    }


    /**
     * Edit the product by the specified SKU.
     *
     * @param string $sku
     * @param array $body
     * @return Response
     * @throws Exception
     */
    public function edit(string $sku, array $body = []): Response
    {
        return $this->put('/products/'.$sku, $body);
    }
}

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
     * @param int|null $pageSize
     * @param int $currentPage
     * @param array $filters
     * @return Response
     * @throws Exception
     */
    public function all(int $pageSize = 50, int $currentPage = 1, array $filters = []): Response
    {
        /**
         * Arguably, the filters we pass should supersede the pageSize and currentPage values.
         * In any case, this package has a significant limitation, but we may not care about it.
         * The fact that it treats query string arguments as a flat array is problematic because the package uses
         * array_merge() to apply "filters" even though many entities (filterGroups, filters, sort groups, et al), are
         * numerically indexed in the query string arguments.
         *
         * The above issue makes it somewhat questionable to add `sku is not null` as an always-on condition.
         * Therefore I am omitting it.
         */
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

            /**
             * AND conditions require a separate filterGroup
             */
            'searchCriteria[filterGroups][1][filters][0][field]' => 'sku',
            'searchCriteria[filterGroups][1][filters][0][conditionType]' => 'notnull',

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
            'fields' => 'items[sku],search_criteria,total_count',
            'searchCriteria[filterGroups][0][filters][0][conditionType]' => 'gteq',
            'searchCriteria[filterGroups][0][filters][0][field]' => 'updated_at',
            'searchCriteria[filterGroups][0][filters][0][value]' => $date,

            /**
             * AND conditions require a separate filterGroup
             */
            'searchCriteria[filterGroups][1][filters][0][field]' => 'sku',
            'searchCriteria[filterGroups][1][filters][0][conditionType]' => 'notnull',

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
    public function show(string $sku): Response
    {
        return $this->get('/products/'.$sku);
    }

    /**
     * Make redundant wrapper functions instead of renaming functions from the original package.
     *
     * @param string $sku
     * @return Response
     * @throws Exception
     */
    public function getBySku(string $sku): Response
    {
        return $this->show($sku);
    }


    /**
     * Get info about product by product ID.
     *
     * @param int $id
     * @return Response|array
     * @throws Exception
     */
    public function getById(int $id)
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
        $result = $this->get('/products',$query);
        $items = $result->json();
        if (isset($items['items'])) {
            return reset($items['items']);
        }
        return $result;
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

    /**
     * Update the Product inventory level
     *
     * @param string $sku
     * @param int $qty
     * @return Response
     * @throws Exception
     */
    public function updateInventoryLevel(string $sku, int $qty): Response
    {
        $data = [
            'product' => [
                'extension_attributes' => [
                    'stock_item' => [
                        'qty' => $qty,
                    ]
                ]
            ]
        ];

        return $this->put('/products/'.$sku, $data);
    }

    /**
     * Get full product option data for the given sku
     */
    public function getOptionsBySku(string $sku): Response
    {
        return $this->get('/products/' . $sku . '/options');
    }

    /**
     * Returns the total count of products updated since the given date.
     *
     * @param string $date
     * @return Response
     * @throws Exception
     */
    public function totalCountModifiedSince(string $date): Response
    {
        $params = [
            'fields' => 'total_count',
            'searchCriteria[filterGroups][0][filters][0][conditionType]' => 'gteq',
            'searchCriteria[filterGroups][0][filters][0][field]' => 'updated_at',
            'searchCriteria[filterGroups][0][filters][0][value]' => $date,

            /**
             * AND conditions require a separate filterGroup
             */
            'searchCriteria[filterGroups][1][filters][0][field]' => 'sku',
            'searchCriteria[filterGroups][1][filters][0][conditionType]' => 'notnull',

            /**
             * Using pageSize 1 makes the request comparatively fast
             */
            'searchCriteria[pageSize]'    => 1,
            'searchCriteria[currentPage]' => 1,
        ];

        return $this->get('/products', $params);
    }
}

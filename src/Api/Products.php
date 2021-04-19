<?php

namespace Grayloon\Magento\Api;

class Products extends AbstractApi
{
    /**
     * The list of Products.
     *
     * @param int $pageSize
     * @param int $currentPage
     *
     * @return  array
     */
    public function all($pageSize = 50, $currentPage = 1, $filters = [])
    {
        return $this->get('/products', array_merge($filters, [
            'searchCriteria[pageSize]'    => $pageSize,
            'searchCriteria[currentPage]' => $currentPage,
        ]));
    }

    /**
     * Get info about product by product SKU.
     *
     * @param string $sku
     *
     * @return array
     */
    public function show($sku)
    {
        return $this->get('/products/'.$sku);
    }

    /**
     * Edit product.
     *
     * @param  string $sku
     * @param  array  $body
     * @return array
     */
    public function edit($sku, $body = [])
    {
        return $this->put('/products/'.$sku, $body);
    }
}

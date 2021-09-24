<?php

namespace Grayloon\Magento\Api;

class ProductAttributes extends AbstractApi
{
    /**
     * Retrieve specific product attribute information.
     *
     * @param  string  $attribute
     * @return array
     */
    public function show($attribute)
    {
        return $this->get('/products/attributes/'.$attribute);
    }

    /**
     * The list of Product Attributes.
     *
     * @param  int  $pageSize
     * @param  int  $currentPage
     * @return array
     */
    public function all($pageSize = 50, $currentPage = 1)
    {
        return $this->get('/products/attributes', [
            'searchCriteria[pageSize]'    => $pageSize,
            'searchCriteria[currentPage]' => $currentPage,
        ]);
    }
}

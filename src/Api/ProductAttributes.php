<?php

namespace Interiordefine\Magento\Api;

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

    /**
     * Fetches all Product attributes specified by $attribute_codes.
     * $attribute_code is optional and can be an array or a string (or null).
     * Believe it or don't, specifying the return type causes an exception.
     *
     * @param string|array|null $attribute_codes
     * @return \Illuminate\Http\Client\Response
     * @throws \Exception
     */
    public function getByAttributeCode($attribute_codes = null)
    {
        $filters = [];
        if (!empty($attribute_codes)) {
            if (is_array($attribute_codes)) {
                $attribute_codes = implode(',', $attribute_codes);
            }

            $filters = [
                'searchCriteria' => [
                    'filter_groups' => [
                        [
                            'filters' => [
                                [
                                    'field' => 'attribute_code',
                                    'condition_type' => 'in',
                                    'value' => $attribute_codes,
                                ]
                            ]
                        ]
                    ]
                ]
            ];
        }
        return $this->get('/products/attributes', $filters);
    }
}

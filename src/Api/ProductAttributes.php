<?php

namespace Interiordefine\Magento\Api;

use Exception;
use Illuminate\Http\Client\Response;

class ProductAttributes extends AbstractApi
{
    /**
     * Retrieve specific product attribute information.
     *
     * @param string $attribute
     * @return Response
     * @throws Exception
     */
    public function show(string $attribute): Response
    {
        return $this->get('/products/attributes/'.$attribute);
    }

    /**
     * The list of Product Attributes.
     *
     * @param int $pageSize
     * @param int $currentPage
     * @return Response
     * @throws Exception
     */
    public function all(int $pageSize = 50, int $currentPage = 1): Response
    {
        return $this->get('/products/attributes', [
            'searchCriteria[pageSize]'    => $pageSize,
            'searchCriteria[currentPage]' => $currentPage,
        ]);
    }

    /**
     * Fetches all Product attributes specified by $attribute_ids.
     * $attribute_ids is optional and can be an array or a string (or null).
     *
     * @param string|array|null $attribute_ids
     * @return Response
     * @throws Exception
     */
    public function getByAttributeId($attribute_ids = null): Response
    {
        $filters = [];
        if (!empty($attribute_ids)) {
            if (is_array($attribute_ids)) {
                $attribute_ids = implode(',', $attribute_ids);
            }

            $filters = [
                'searchCriteria' => [
                    'filter_groups' => [
                        [
                            'filters' => [
                                [
                                    'field' => 'attribute_id',
                                    'condition_type' => 'in',
                                    'value' => $attribute_ids,
                                ]
                            ]
                        ]
                    ]
                ]
            ];
        }
        return $this->get('/products/attributes', $filters);
    }

    /**
     * Fetches all Product attributes specified by $attribute_codes.
     * $attribute_codes is optional and can be an array or a string (or null).
     * Believe it or don't, specifying the return type causes an exception.
     *
     * @param string|array|null $attribute_codes
     * @return Response
     * @throws Exception
     */
    public function getByAttributeCode($attribute_codes = null): Response
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

    /**
     * Fetches all Product Custom Attributes with lookup values.
     * I.e., attributes with frontend_input = boolean|select|multiselect.
     * @return Response
     * @throws Exception
     */
    public function getAttributesWithLookupValues(): Response
    {
        $filters = [
            'searchCriteria' => [
                'filter_groups' => [
                    [
                        'filters' => [
                            [
                                'field' => 'frontend_input',
                                'condition_type' => 'in',
                                'value' => 'boolean,select,multiselect',
                            ],
                        ],
                    ],
                ],
                'sortOrders' => [
                    [
                        'field' => 'attribute_code',
                        'direction' => 'ASC',
                    ],
                ],
            ],
            'fields' => 'items[attribute_code,options]',
        ];
        return $this->get('/products/attributes', $filters);
    }
}

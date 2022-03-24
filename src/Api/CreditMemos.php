<?php

namespace Grayloon\Magento\Api;

class CreditMemos extends AbstractApi
{
    /**
     * Lists credit memos that match specified search criteria.
     *
     * @param  int  $pageSize
     * @param  int  $currentPage
     * @param  array  $filters
     * @return array
     */
    public function all($pageSize = 50, $currentPage = 1, $filters = [])
    {
        return $this->get('/creditmemos', array_merge($filters, [
            'searchCriteria[pageSize]'    => $pageSize,
            'searchCriteria[currentPage]' => $currentPage,
        ]));
    }

    /**
     * Loads a specified credit memo.
     *
     * @param  int  $creditMemoId
     * @return array
     */
    public function show($creditMemoId)
    {
        return $this->get('/creditmemo/'.$creditMemoId);
    }

    /**
     * Performs persist operations for a specified credit memo.
     *
     * @see https://magento.redoc.ly/2.4.3-admin/tag/creditmemo#operation/salesCreditmemoRepositoryV1SavePost
     *
     * @param  array  $entity
     * @return array
     */
    public function edit($entity = [])
    {
        return $this->post('/creditmemo', [
            'entity' => $entity,
        ]);
    }
}

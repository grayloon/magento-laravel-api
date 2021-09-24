<?php

namespace Grayloon\Magento\Api;

class CustomerGroups extends AbstractApi
{
    /**
     * Show the customer group by the provided ID.
     *
     * @param  int  $id
     * @return array
     */
    public function show($id)
    {
        return $this->get('/customerGroups/'.$id);
    }

    /**
     * Save the customer group by the provided ID.
     *
     * @param  int  $id
     * @param  array  $customerGroupRepositoryV1SavePutBody
     * @return array
     */
    public function saveGroup($id, $customerGroupRepositoryV1SavePutBody = [])
    {
        return $this->put('/customerGroups/'.$id, $customerGroupRepositoryV1SavePutBody);
    }

    /**
     * Delete customer group by the provided ID.
     *
     * @param  int  $id
     * @return array
     */
    public function deleteGroup($id)
    {
        return $this->delete('/customerGroups/'.$id);
    }

    /**
     * Save/Create Customer Group.
     *
     * @param  array  $customerGroupRepositoryV1SavePutBody
     * @return array
     */
    public function createGroup($customerGroupRepositoryV1SavePostBody = [])
    {
        return $this->post('/customerGroups', $customerGroupRepositoryV1SavePostBody);
    }

    /**
     * Search the Customer Groups.
     *
     * @param  int  $pageSize
     * @param  int  $currentPage
     * @param  array  $filters
     * @return array
     */
    public function search($pageSize = 50, $currentPage = 1, $filters = [])
    {
        return $this->get('/customerGroups/search', array_merge($filters, [
            'searchCriteria[pageSize]'    => $pageSize,
            'searchCriteria[currentPage]' => $currentPage,
        ]));
    }

    /**
     * Get the default Customer Group.
     *
     * @return array
     */
    public function default()
    {
        return $this->get('/customerGroups/default');
    }

    /**
     * Set the system default customer group.
     *
     * @param  int  $id
     * @return array
     */
    public function setDefault($id)
    {
        return $this->put('/customerGroups/default/'.$id);
    }

    /**
     * Check if customer group can be deleted.
     *
     * @param  int  $id
     * @return array|bool
     */
    public function permissions($id)
    {
        return $this->get('/customerGroups/'.$id.'/permissions');
    }
}

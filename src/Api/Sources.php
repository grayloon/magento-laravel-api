<?php

namespace Grayloon\Magento\Api;

class Sources extends AbstractApi
{
    /**
     * inventoryApiSourcesRepositoryV1
     * All of paginated source items.
     *
     * @param  int  $pageSize
     * @param  int  $currentPage
     * @param  array  $filters
     *
     * @return array
     */
    public function all($pageSize = 50, $currentPage = 1, $filters = [])
    {
        return $this->get('/inventory/sources', array_merge($filters, [
            'searchCriteria[pageSize]'    => $pageSize,
            'searchCriteria[currentPage]' => $currentPage,
        ]));
    }

    public function bySourceName(String $name = 'default')
    {
        return $this->get('/inventory/sources/'.$name);
    }
}

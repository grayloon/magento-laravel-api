<?php

namespace Grayloon\Magento\Api;

class Store extends AbstractApi
{
    /**
     * Retrieve list of all store configs.
     *
     * @return array
     */
    public function storeConfigs()
    {
        return $this->get('/store/storeConfigs');
    }
}

<?php

namespace Grayloon\Magento\Api;

class Stores extends AbstractApi
{
    /**
     * Retrieve list of all websites.
     *
     * @return array
     */
    public function websites()
    {
        return $this->get('/store/websites');
    }
}

<?php

namespace Grayloon\Magento\Api;

class CartItems extends AbstractApi
{
    /**
     * Lists items that are assigned to a specified customer cart. Must have a store code.
     *
     * @return array
     */
    public function mine()
    {
        $this->validateSingleStoreCode();

        return $this->get('/carts/mine/items');
    }
}

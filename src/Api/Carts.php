<?php

namespace Grayloon\Magento\Api;

class Carts extends AbstractApi
{
    /**
     * Returns information for the cart for the authenticated customer. Must have a store code.
     *
     * @return array
     */
    public function mine()
    {
        $this->validateStoreCode();

        return $this->get('/carts/mine');
    }
}

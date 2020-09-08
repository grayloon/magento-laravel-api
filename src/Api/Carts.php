<?php

namespace Grayloon\Magento\Api;

use Exception;

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

    /**
     * Validates the usage of the Carts API.
     *
     * @throws \Exception
     * @return void
     */
    protected function validateStoreCode()
    {
        if ($this->magento->storeCode === 'all') {
            throw new Exception(__('You must pass a single store code. "all" cannot be used.'));
        }

        return $this;
    }
}

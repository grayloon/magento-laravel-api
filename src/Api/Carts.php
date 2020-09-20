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
        $this->validateSingleStoreCode();

        return $this->get('/carts/mine');
    }

    /**
     * Estimate shipping by address and return list of available shipping methods.
     *
     * @param  array  $body
     * @return array
     */
    public function estimateShippingMethods($body = [])
    {
        $this->validateSingleStoreCode();

        return $this->post('/carts/mine/estimate-shipping-methods', $body);
    }

    /**
     * Calculate quote totals based on address and shipping method.
     *
     * @param  array  $body
     * @return array
     */
    public function totalsInformation($body = [])
    {
        $this->validateSingleStoreCode();

        return $this->post('/carts/mine/totals-information', $body);
    }

    /**
     * Save the total shipping information.
     *
     * @param  array  $body
     * @return array
     */
    public function shippingInformation($body = [])
    {
        $this->validateSingleStoreCode();

        return $this->post('/carts/mine/shipping-information', $body);
    }
}

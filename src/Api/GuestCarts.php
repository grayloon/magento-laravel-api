<?php

namespace Grayloon\Magento\Api;

class GuestCarts extends AbstractApi
{
    /**
     * Enable an customer or guest user to create an empty cart and quote for an anonymous customer.
     *
     * @return string
     */
    public function create()
    {
        return $this->post('/guest-carts');
    }

    /**
     * Enable a guest user to return information for a specified cart.
     *
     * @return array
     */
    public function cart($cartId)
    {
        return $this->get('/guest-carts/'.$cartId);
    }

    /**
     * List items that are assigned to a specified cart.
     *
     * @return array
     */
    public function items($cartId)
    {
        return $this->get('/guest-carts/'.$cartId.'/items');
    }
}

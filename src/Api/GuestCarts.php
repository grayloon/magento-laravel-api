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
     * List items that are assigned to a specified cart.
     *
     * @return string
     */
    public function items($cartId)
    {
        return $this->post('/guest-carts/'.$cartId.'/items');
    }
}

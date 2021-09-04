<?php

namespace Grayloon\Magento\Api;

class Order extends AbstractApi
{

    /**
     * Creates invoice for order.
     *
     * @param  int  $orderId
     *
     * @return  array
     */
    public function invoice($orderId,$capture= true,$notify= true,$appendComment=true,
                            $extension_attributes="",$comment="",$is_visible_on_front= 0)
    {
        return $this->post('/orders/'.$orderId. '/invoice',[
            'capture' => $capture,
            'notify' => $notify,
            'appendComment' => $appendComment,
            'comment'=>[
                'extension_attributes'=>$extension_attributes,
                'comment'=>$comment,
                'is_visible_on_front'=>0,
            ]
        ]);
    }
}

<?php

namespace Grayloon\Magento\Api;

use Illuminate\Http\Client\Response;

class Order extends AbstractApi
{

    /**
     * Creates invoice for order.
     *
     * @param int $orderId
     *
     * @return  Response
     */
    public function invoice(int $orderId, $capture= true, $notify= true, $appendComment=true,
                                $extension_attributes="", $comment="", $is_visible_on_front= 0): Response
    {
        return $this->post('/order/'.$orderId. '/invoice',[
            'capture' => $capture,
            'notify' => $notify,
            'appendComment' => $appendComment,
            'comment'=>[
                'extension_attributes'=>$extension_attributes,
                'comment'=>$comment,
                'is_visible_on_front'=>$is_visible_on_front,
            ]
        ]);
    }
}

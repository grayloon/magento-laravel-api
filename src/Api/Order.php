<?php

namespace Grayloon\Magento\Api;

use Illuminate\Http\Client\Response;

class Order extends AbstractApi
{

    /**
     * Creates invoice for order.
     *
     * @param int $orderId
     * @param bool $capture
     * @param bool $notify
     * @param bool $appendComment
     * @param string $extension_attributes
     * @param string $comment
     * @param int $is_visible_on_front
     * @return  Response
     */
    public function invoice(int $orderId, bool $capture = true, bool $notify = true, bool $appendComment = true,
                            string $extension_attributes = '', string $comment = '', int $is_visible_on_front = 0): Response
    {
        return $this->post('/order/'.$orderId.'/invoice', [
            'capture' => $capture,
            'notify' => $notify,
            'appendComment' => $appendComment,
            'comment'=>[
                'extension_attributes'=>$extension_attributes,
                'comment'=>$comment,
                'is_visible_on_front'=>$is_visible_on_front,
            ],
        ]);
    }
}

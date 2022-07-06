<?php

namespace Grayloon\Magento\Api;

use Exception;
use Illuminate\Http\Client\Response;

class AttributeMetadata extends AbstractApi
{
    /**
     * Get attribute metadata for a customer address.
     *
     * @return Response
     * @throws Exception
     */
    public function customerAddress(): Response
    {
        return $this->get('/attributeMetadata/customerAddress');
    }
}

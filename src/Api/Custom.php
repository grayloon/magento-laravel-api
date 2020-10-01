<?php

namespace Grayloon\Magento\Api;

use Grayloon\Magento\Magento;

class Custom extends AbstractApi
{
    /**
     * @var string Magento API endpoint
     */
    protected $endpoint;

    /**
     * Custom constructor.
     * 
     * @param string $endpoint
     */
    public function __construct(string $endpoint, Magento $magento)
    {
        $this->endpoint = $endpoint;

        parent::__construct($magento);
    }

    /**
     * Dynamic call to passthrough.
     *
     * @param  string $method
     * @param  array  $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        if ($method == 'get' || $method == 'post') {
            $args[0] = rtrim($this->endpoint, '/') . '/' . $args[0];
        }

        return call_user_func_array([$this, $method], $args);
    }
}

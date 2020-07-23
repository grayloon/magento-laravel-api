<?php

namespace Grayloon\Magento\Api;

use Grayloon\Magento\Magento;
use Illuminate\Support\Facades\Http;

abstract class AbstractApi
{
    /**
     * The Magento Client instance.
     *
     * @var \Grayloon\Magento\Magneto
     */
    public $magento;

    /**
     * The API request URI.
     *
     * @var string
     */
    public $apiRequest;

    /**
     * @param \Grayloon\Magento\Magento  $magento
     */
    public function __construct(Magento $magento)
    {
        $this->magento = $magento;

        $this->apiRequest = $this->magento->baseUrl . config('magento.base_path');

        if ($this->magento->versionIncluded) {
            $this->apiRequest .= '/'.config('magento.version');
        }
    }

    /**
     * Send a GET request with query parameters.
     *
     * @param  string  $path
     * @param  string  $parameters
     *
     * @return mixed
     */
    protected function get($path, $parameters = [])
    {
        return Http::withToken($this->magento->token)
            ->get($this->apiRequest . $path, $parameters)
            ->json();
    }

    /**
     * Send a POST request with query parameters.
     *
     * @param  string  $path
     * @param  string  $parameters
     *
     * @return mixed
     */
    protected function post($path, $parameters = [])
    {
        return Http::withToken($this->magento->token)
            ->post($this->apiRequest . $path, $parameters)
            ->json();
    }
}

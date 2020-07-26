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
     * The API request Uri builder.
     *
     * @var string
     */
    public $apiRequest;

    public function __construct(Magento $magento)
    {
        $this->magento = $magento;
        $this->apiRequest = $this->constructRequest();
    }
    
    /**
     * The initial API request before the builder.
     *
     * @return string
     */
    protected function constructRequest()
    {
        $request = $this->magento->baseUrl;
        $request .= '/'.$this->magento->basePath;
        $request .= '/'.$this->magento->storeCode;

        if ($this->magento->versionIncluded) {
            $request .= '/'.$this->magento->version;
        }

        return $request;
    }

    /**
     * Send a GET request with query parameters.
     *
     * @param  string  $path
     * @param  string  $parameters
     *
     * @return \Illuminate\Http\Client\Response
     */
    protected function get($path, $parameters = [])
    {
        return Http::withToken($this->magento->token)
            ->get($this->apiRequest.$path, $parameters);
    }

    /**
     * Send a POST request with query parameters.
     *
     * @param  string  $path
     * @param  string  $parameters
     *
     * @return \Illuminate\Http\Client\Response
     */
    protected function post($path, $parameters = [])
    {
        return Http::withToken($this->magento->token)
            ->post($this->apiRequest.$path, $parameters);
    }
}

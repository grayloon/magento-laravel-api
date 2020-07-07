<?php

namespace Grayloon\Magento\Api;

use Grayloon\Magento\Magento;
use Illuminate\Support\Facades\Http;

abstract class AbstractApi
{
    public $versionIncluded = true;
    
    /**
     * The Magento Client instance.
     *
     * @var \Grayloon\Magento\Magneto
     */
    public $magento;

    /**
     * @param \Grayloon\Magento\Magento  $magento
     */
    public function __construct(Magento $magento)
    {
        $this->magento = $magento;
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
        $baseApi = config('magento.base_path');

        if ($this->versionIncluded) {
            $baseApi = $baseApi . '/' . config('magento.version');
        }
        
        return Http::withOptions([
                'verify' => false, // temp remove SSL checks.
            ])
            ->withToken($this->magento->token)
            ->get($this->magento->baseUrl . $baseApi . $path, $parameters)
            ->json();
    }
}

<?php

namespace Grayloon\Magento;

use Grayloon\Magento\Api\Custom;
use Illuminate\Support\Str;
use InvalidArgumentException;

class Magento
{
    /**
     * The Base URL of the Magento 2 store.
     *
     * @var string
     */
    public $baseUrl;

    /**
     * The Access Token defined from the Magento 2 application.
     *
     * @var string|null
     */
    public $token;

    /**
     * Determines if the API Version is included in the request.
     *
     * @var bool
     */
    public $versionIncluded = true;

    /**
     * The specified API version to use in the request.
     *
     * @var string
     */
    public $version;

    /**
     * The Magento 2 API base path.
     *
     * @var string
     */
    public $basePath;

    /**
     * The Magento 2 store code.
     *
     * @var string
     */
    public $storeCode;

    /**
     * Magento constructor.
     *
     * @param string $baseUrl
     * @param string $token
     */
    public function __construct($baseUrl = null, $token = null)
    {
        $this->baseUrl = $baseUrl ?: config('magento.base_url');
        $this->token = $token ?: config('magento.token');
        $this->version = config('magento.version') ?? 'V1';
        $this->basePath = config('magento.base_path') ?? 'rest';
        $this->storeCode = config('magento.store_code') ?? 'all';
    }

    /**
     * The API method to be called on the Magento 2 API.
     *
     * @param  string  $name
     *
     * @throws InvalidArgumentException
     * @return \Grayloon\Magento\Api\AbstractApi
     */
    public function api($name)
    {
        $className = $name;
        $apiMethodExists = class_exists($className = "\Grayloon\Magento\Api\\".Str::ucfirst($className));

        if (! $apiMethodExists) {
            return new Custom($name, $this);
        }

        return new $className($this);
    }
}

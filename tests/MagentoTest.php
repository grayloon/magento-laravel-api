<?php

namespace Interiordefine\Magento\Tests;

use Illuminate\Support\Facades\Config;
use Interiordefine\Magento\Magento;

class MagentoTest extends TestCase
{
    /**
     * Test by configuring the settings in the constructor.
     *
     * @return void
     **/
    public function test_by_configuring_the_settings_in_the_constructor()
    {
        $api = new Magento('baseUrl-constructor', 'token-constructor', 'version-constructor', 'basePath-constructor', 'storeCode-constructor');

        $this->assertEquals($api->baseUrl, 'baseUrl-constructor');
        $this->assertEquals($api->token, 'token-constructor');
        $this->assertEquals($api->version, 'version-constructor');
        $this->assertEquals($api->basePath, 'basePath-constructor');
        $this->assertEquals($api->storeCode, 'storeCode-constructor');
    }

    /**
     * Test by configuring the settings in the laravel configs.
     *
     * @return void
     **/
    public function test_by_configuring_the_settings_in_the_laravel_configs()
    {
        Config::set('magento.base_url', 'baseUrl-config');
        Config::set('magento.token', 'token-config');
        Config::set('magento.version', 'version-config');
        Config::set('magento.base_path', 'basePath-config');
        Config::set('magento.store_code', 'storeCode-config');

        $api = new Magento();

        $this->assertEquals($api->baseUrl, 'baseUrl-config');
        $this->assertEquals($api->token, 'token-config');
        $this->assertEquals($api->version, 'version-config');
        $this->assertEquals($api->basePath, 'basePath-config');
        $this->assertEquals($api->storeCode, 'storeCode-config');
    }

    /**
     * Test without configuring the settings.
     *
     * @return void
     **/
    public function test_without_configuring_the_settings()
    {
        $api = new Magento();

        $this->assertNull($api->baseUrl);
        $this->assertNull($api->token);

        $defaultVersion = 'V1';
        $this->assertEquals($api->version, $defaultVersion);

        $defaultBasePath = 'rest';
        $this->assertEquals($api->basePath, $defaultBasePath);

        $defaultStoreCode = 'all';
        $this->assertEquals($api->storeCode, $defaultStoreCode);
    }

    /**
     * Test by configuring the settings in the constructor have priority.
     *
     * @return void
     **/
    public function test_by_configuring_the_settings_in_the_constructor_have_priority()
    {
        Config::set('magento.base_url', 'baseUrl-config');
        Config::set('magento.token', 'token-config');
        Config::set('magento.version', 'version-config');
        Config::set('magento.base_path', 'basePath-config');
        Config::set('magento.store_code', 'storeCode-config');

        $api = new Magento('baseUrl-constructor', 'token-constructor', 'version-constructor', 'basePath-constructor', 'storeCode-constructor');

        $this->assertEquals($api->baseUrl, 'baseUrl-constructor');
        $this->assertEquals($api->token, 'token-constructor');
        $this->assertEquals($api->version, 'version-constructor');
        $this->assertEquals($api->basePath, 'basePath-constructor');
        $this->assertEquals($api->storeCode, 'storeCode-constructor');
    }
}

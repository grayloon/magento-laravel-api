<?php

namespace Grayloon\Magento\Tests;

use Grayloon\Magento\Api\AbstractApi;
use Grayloon\Magento\Magento;

class AbstractApiTest extends TestCase
{
    public function test_can_properly_construct_api_request()
    {
        $api = new FakeApiClass((new Magento()));

        $this->assertEquals('/rest/all/V1', $api->apiRequest);
    }

    public function test_adds_provided_base_url_to_api_request()
    {
        $api = new FakeApiClass((new Magento('example.com')));

        $this->assertEquals('example.com/rest/all/V1', $api->apiRequest);
    }
}

class FakeApiClass extends AbstractApi
{
    //
}

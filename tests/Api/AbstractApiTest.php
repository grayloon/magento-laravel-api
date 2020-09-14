<?php

namespace Grayloon\Magento\Tests;

use Grayloon\Magento\Api\AbstractApi;
use Grayloon\Magento\Magento;
use Illuminate\Support\Facades\Http;

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

    public function test_expect_throw_exception_on_get_api_error()
    {
        $this->expectException('exception');
        Http::fake(['message' => 'There was an error.'], 500);
        
        (new FakeApiClass((new Magento('example.com'))))->fakeGetEndpoint();
    }

    public function test_expect_throw_exception_on_post_api_error()
    {
        $this->expectException('exception');
        Http::fake(['message' => 'There was an error.'], 500);
        
        (new FakeApiClass((new Magento('example.com'))))->fakePostEndpoint();
    }
}

class FakeApiClass extends AbstractApi
{
    public function fakeGetEndpoint()
    {
        $this->get('/foo');
    }

    public function fakePostEndpoint()
    {
        $this->post('/bar');
    }
}

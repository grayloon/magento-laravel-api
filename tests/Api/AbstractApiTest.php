<?php

namespace Interiordefine\Magento\Tests;

use Illuminate\Support\Facades\Http;
use Interiordefine\Magento\Api\AbstractApi;
use Interiordefine\Magento\MagentoFacade;

class AbstractApiTest extends TestCase
{
    public function test_can_properly_construct_api_request()
    {
        $api = new FakeApiClass(MagentoFacade::getFacadeRoot());

        $this->assertEquals('/rest/all/V1', $api->apiRequest);
    }

    public function test_adds_provided_base_url_to_api_request()
    {
        $api = new FakeApiClass(MagentoFacade::setBaseUrl('example.com'));

        $this->assertEquals('example.com/rest/all/V1', $api->apiRequest);
    }

    public function test_expect_throw_exception_on_get_api_error()
    {
        $this->expectException('exception');
        Http::fake([
            '*' => Http::response([
                'message' => 'There was an error.',
            ], 500),
        ]);

        (new FakeApiClass(MagentoFacade::setBaseUrl('example.com')))->fakeGetEndpoint();
    }

    public function test_expect_throw_exception_on_post_api_error()
    {
        $this->expectException('exception');
        Http::fake([
            '*' => Http::response([
                'message' => 'There was an error.',
            ], 500),
        ]);

        (new FakeApiClass(MagentoFacade::setBaseUrl('example.com')))->fakePostEndpoint();
    }

    public function test_expect_message_body_from_array_on_exception_throw()
    {
        $this->expectException('exception');
        $this->expectExceptionMessage('There was an error.');
        Http::fake([
            '*' => Http::response([
                'message' => 'There was an error.',
            ], 500),
        ]);

        (new FakeApiClass(MagentoFacade::setBaseUrl('example.com')))->fakePostEndpoint();
    }

    public function test_400_error_does_not_throw_exception()
    {
        Http::fake([
            '*' => Http::response([
                'message' => 'Unauthorized',
            ], 401),
        ]);

        $this->assertNull((new FakeApiClass(MagentoFacade::setBaseUrl('foo.com')))->fakeGetEndpoint());
    }

    public function test_200_error_does_not_throw_exception()
    {
        Http::fake([
            '*' => Http::response([
                'message' => 'Ready to Rock!',
            ], 200),
        ]);

        $this->assertNull((new FakeApiClass(MagentoFacade::setBaseUrl('foo.com')))->fakeGetEndpoint());
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

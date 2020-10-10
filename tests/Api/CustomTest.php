<?php

namespace Grayloon\Magento\Tests;

use Grayloon\Magento\MagentoFacade;
use Illuminate\Support\Facades\Http;

class CustomTest extends TestCase
{
    public function test_can_get_custom_endpoint()
    {
        Http::fake([
            '*rest/all/V1/foo/bar' => Http::response([], 200),
        ]);

        $customApi = MagentoFacade::api('/foo')->get('bar');

        $this->assertTrue($customApi->ok());
    }

    public function test_can_post_custom_endpoint()
    {
        Http::fake([
            '*rest/all/V1/foo/bar' => Http::response([], 200),
        ]);

        $customApi = MagentoFacade::api('/foo')->post('bar');

        $this->assertTrue($customApi->ok());
    }

    public function test_custom_post_endpoint_passes_params()
    {
        Http::fake([
            '*rest/all/V1/foo/bar' => Http::response([], 200, ['baz' => 'test']),
        ]);

        $customApi = MagentoFacade::api('/foo')->post('bar', [
            'baz' => 'test',
        ]);

        $this->assertTrue($customApi->ok());
    }
}

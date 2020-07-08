<?php

namespace Grayloon\Magento\Tests\Http\Controllers;

use Grayloon\Magento\Tests\TestCase;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

class MagentoProductsControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware([Authorize::class, VerifyCsrfToken::class]);
    }

    public function test_can_successfully_send_request_to_update_product_over_api()
    {
        $this->getJson(route('laravel-magento-api.products.update', 'foo'))
         ->assertSuccessful();
    }
}

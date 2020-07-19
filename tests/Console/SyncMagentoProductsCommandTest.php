<?php

namespace Grayloon\Magento\Tests\Console;

use Grayloon\Magento\Jobs\SyncMagentoProducts;
use Grayloon\Magento\Tests\TestCase;
use Illuminate\Support\Facades\Queue;

class SyncMagentoProductsCommandTest extends TestCase
{
    public function test_magento_products_command_fires_product_job()
    {
        Queue::fake();

        $this->artisan('magento:sync-products');

        Queue::assertPushed(SyncMagentoProducts::class);
    }
}

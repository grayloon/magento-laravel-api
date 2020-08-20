<?php

namespace Grayloon\Magento\Tests\Console;

use Grayloon\Magento\Jobs\SyncMagentoProductQuantity;
use Grayloon\Magento\Tests\TestCase;
use Illuminate\Support\Facades\Queue;

class SyncMagentoProductQuantityCommandTest extends TestCase
{
    public function test_magento_quantity_command_fires_job()
    {
        Queue::fake();

        $this->artisan('magento:sync-product-quantity');

        Queue::assertPushed(SyncMagentoProductQuantity::class);
    }
}

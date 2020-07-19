<?php

namespace Grayloon\Magento\Tests\Console;

use Grayloon\Magento\Jobs\SyncMagentoCategories;
use Grayloon\Magento\Tests\TestCase;
use Illuminate\Support\Facades\Queue;

class SyncMagentoCategoriesCommandTest extends TestCase
{
    public function test_magento_categories_command_fires_product_job()
    {
        Queue::fake();

        $this->artisan('magento:sync-categories');

        Queue::assertPushed(SyncMagentoCategories::class);
    }
}

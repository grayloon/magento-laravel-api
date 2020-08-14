<?php

namespace Grayloon\Magento\Tests\Console;

use Grayloon\Magento\Jobs\SyncMagentoProductLinkTypes;
use Grayloon\Magento\Tests\TestCase;
use Illuminate\Support\Facades\Queue;

class SyncMagentoProductLinkTypesCommandTest extends TestCase
{
    public function test_magento_magento_product_link_types_command_fires_job()
    {
        Queue::fake();

        $this->artisan('magento:sync-product-link-types');

        Queue::assertPushed(SyncMagentoProductLinkTypes::class);
    }
}

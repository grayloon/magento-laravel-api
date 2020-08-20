<?php

namespace Grayloon\Magento\Console;

use Grayloon\Magento\Jobs\SyncMagentoProductQuantity;
use Illuminate\Console\Command;

class SyncMagentoProductQuantityCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'magento:sync-product-quantity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates product quantity from the Magento 2 API. Products must be available.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        SyncMagentoProductQuantity::dispatch();

        $this->info('Successfully launched job to update all magento product quantity.');
    }
}

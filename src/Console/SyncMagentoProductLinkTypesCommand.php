<?php

namespace Grayloon\Magento\Console;

use Grayloon\Magento\Jobs\SyncMagentoProductLinkTypes;
use Illuminate\Console\Command;

class SyncMagentoProductLinkTypesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'magento:sync-product-link-types';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports the product link types from the Magneto 2 API';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        SyncMagentoProductLinkTypes::dispatch();

        $this->info('Successfully launched job to import the magento link types.');
    }
}

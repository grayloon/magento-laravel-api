<?php

namespace Grayloon\Magento\Console;

use Grayloon\Magento\Jobs\SyncMagentoCustomers;
use Illuminate\Console\Command;

class SyncMagnetoCustomersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'magento:sync-customers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports the customer data from the Magneto 2 API';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        SyncMagentoCustomers::dispatch();

        $this->info('Successfully launched job to import magento customers.');
    }
}

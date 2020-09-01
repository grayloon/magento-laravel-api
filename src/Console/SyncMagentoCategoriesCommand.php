<?php

namespace Grayloon\Magento\Console;

use Grayloon\Magento\Jobs\SyncMagentoCategories;
use Illuminate\Console\Command;

class SyncMagentoCategoriesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'magento:sync-categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports the category data from the Magneto 2 API';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        SyncMagentoCategories::dispatch();

        $this->info('Successfully launched job to import magento categories.');
    }
}

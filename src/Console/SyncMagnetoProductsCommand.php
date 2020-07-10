<?php

namespace Grayloon\Magento\Console;

use Grayloon\Magento\Jobs\SyncMagentoProducts;
use Illuminate\Console\Command;

class SyncMagnetoProductsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'magento:sync-products';

    /**
     * The progress bar to display to the user.
     *
     * @var object
     */
    public $bar;

    /**
     * The total count of records found.
     *
     * @var int
     */
    public $totalCount = 0;

    /**
     * The amount of records per API request.
     *
     * @var int
     */
    public $pageSize = 50;

    /**
     * The paginated page to request.
     *
     * @var int
     */
    public $currentPage = 1;

    /**
     * The total amount of pages to paginate.
     *
     * @var int
     */
    public $totalPages = 1;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports the product data from the Magneto 2 API';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        SyncMagentoProducts::dispatch();

        $this->info('Successfully launched job to import magento products.');
    }
}

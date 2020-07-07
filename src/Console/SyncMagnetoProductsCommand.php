<?php

namespace Grayloon\Magento\Console;

use Grayloon\Magento\Jobs\SyncMagentoProducts;
use Grayloon\Magento\Magento;
use Illuminate\Console\Command;
use Grayloon\Magento\Models\MagentoProduct;
use Grayloon\Magento\Models\MagentoExtAttribute;
use Grayloon\Magento\Models\MagentoCustAttribute;
use Grayloon\Magento\Models\MagentoExtAttributeType;
use Grayloon\Magento\Models\MagentoCustAttributeType;

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
     * @var integer
     */
    public $totalCount = 0;

    /**
     * The amount of records per API request.
     *
     * @var integer
     */
    public $pageSize = 50;

    /**
     * The paginated page to request.
     *
     * @var integer
     */
    public $currentPage = 1;

    /**
     * The total amount of pages to paginate.
     *
     * @var integer
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
